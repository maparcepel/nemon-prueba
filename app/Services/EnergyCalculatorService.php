<?php

namespace App\Services;

use App\Exceptions\InvalidFormulaException;
use App\Exceptions\NoDataFoundException;
use App\Models\Consumption;
use App\Models\Price;

class EnergyCalculatorService
{
    public function calculateIndexedPrice(array $data): array
    {
        ['start_date' => $startDate, 'end_date' => $endDate, 'formula' => $formula] = $data;

        $consumptions = $this->getConsumptionsInRange($startDate, $endDate);
        $prices = $this->getPricesInRange($startDate, $endDate);

        $this->validateDataExists($consumptions, $prices, $startDate, $endDate);

        return $this->performCalculation($consumptions, $prices, $formula);
    }

    private function getConsumptionsInRange(string $startDate, string $endDate)
    {
        return Consumption::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy('date');
    }

    private function getPricesInRange(string $startDate, string $endDate)
    {
        return Price::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy('date');
    }

    private function validateDataExists($consumptions, $prices, $startDate, $endDate): void
    {
        if ($consumptions->isEmpty() || $prices->isEmpty()) {
            throw new NoDataFoundException(
                "No existen registros de consumos o precios para todo el rango de fechas especificado entre {$startDate} y {$endDate}"
            );
        }
    }

    private function performCalculation($consumptions, $prices, string $formula): array
    {
        $totalImportes = 0;
        $totalConsumos = 0;

        foreach ($consumptions as $consumption) {
            $price = $prices->get($consumption->date);

            if (! $price) {
                continue;
            }

            for ($hora = 1; $hora <= 25; $hora++) {
                $campoHora = "h{$hora}";

                $precioHora = $price->$campoHora;
                $consumoHora = $consumption->$campoHora;

                if ($consumoHora > 0) {
                    $precioCalculado = $this->evaluateFormula($formula, $precioHora);
                    $importeHora = $precioCalculado * $consumoHora;

                    $totalImportes += $importeHora;
                    $totalConsumos += $consumoHora;
                }
            }
        }

        $precioIndexado = $totalConsumos > 0 ? $totalImportes / $totalConsumos : 0;

        return [
            'price_indexed' => round($precioIndexado, 6),
            'total_consumption' => round($totalConsumos, 3),
            'total_amount' => round($totalImportes, 2),
            'calculation_details' => [
                'days_processed' => $consumptions->count(),
                'formula_used' => $formula,
            ],
        ];
    }

    private function evaluateFormula(string $formula, float $marketPrice): float
    {
        try {
            $evaluableFormula = str_replace('[OMIE_MD]', $marketPrice, $formula);

            $this->validateFormula($evaluableFormula);

            $result = eval("return {$evaluableFormula};");

            if (! is_numeric($result)) {
                throw new InvalidFormulaException('La fórmula no produce un resultado numérico válido');
            }

            return (float) $result;

        } catch (\ParseError $e) {
            throw new InvalidFormulaException('Error de sintaxis en la fórmula: '.$e->getMessage());
        } catch (\DivisionByZeroError $e) {
            throw new InvalidFormulaException('División por cero en la fórmula');
        }
    }

    private function validateFormula(string $formula): void
    {
        if (! preg_match('/^[0-9+\-*\/\s().\[\]]+$/', str_replace('[OMIE_MD]', '', $formula))) {
            throw new InvalidFormulaException('La fórmula contiene caracteres no permitidos');
        }
    }
}
