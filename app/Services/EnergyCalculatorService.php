<?php

namespace App\Services;

use App\Exceptions\InvalidFormulaException;
use App\Exceptions\NoDataFoundException;
use App\Models\Consumption;
use App\Models\Price;
use Illuminate\Support\Collection;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class EnergyCalculatorService
{
    private ExpressionLanguage $expressionLanguage;

    /**
     * Maximum number of hours per day in the consumption/price data.
     */
    private const HOURS_PER_DAY = 25;

    public function __construct()
    {
        $this->expressionLanguage = new ExpressionLanguage;
    }

    /**
     * Calculate the indexed price for energy consumption between two dates.
     *
     * @param  array{start_date: string, end_date: string, formula: string}  $data  The calculation parameters
     * @return array{price_indexed: float, total_consumption: float, total_amount: float, calculation_details: array}
     *
     * @throws NoDataFoundException When no consumption or price data exists for the date range
     * @throws InvalidFormulaException When the formula is invalid or fails evaluation
     */
    public function calculateIndexedPrice(array $data): array
    {
        ['start_date' => $startDate, 'end_date' => $endDate, 'formula' => $formula] = $data;

        // Validate formula syntax early (before processing data)
        $this->validateAndTestFormula($formula);

        $consumptions = $this->getConsumptionsInRange($startDate, $endDate);
        $prices = $this->getPricesInRange($startDate, $endDate);

        $this->validateDataExists($consumptions, $prices, $startDate, $endDate);

        return $this->performCalculation($consumptions, $prices, $formula);
    }

    /**
     * Retrieve consumption records for the specified date range.
     *
     * @param  string  $startDate  Start date in Y-m-d format
     * @param  string  $endDate  End date in Y-m-d format
     * @return Collection<string, Consumption> Consumptions keyed by date
     */
    private function getConsumptionsInRange(string $startDate, string $endDate): Collection
    {
        return Consumption::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn ($consumption) => (string) $consumption->date);
    }

    /**
     * Retrieve price records for the specified date range.
     *
     * @param  string  $startDate  Start date in Y-m-d format
     * @param  string  $endDate  End date in Y-m-d format
     * @return Collection<string, Price> Prices keyed by date
     */
    private function getPricesInRange(string $startDate, string $endDate): Collection
    {
        return Price::whereBetween('date', [$startDate, $endDate])
            ->get()
            ->keyBy(fn ($price) => (string) $price->date);
    }

    /**
     * Validate that consumption and price data exist for the date range.
     *
     * @param  Collection<string, Consumption>  $consumptions
     * @param  Collection<string, Price>  $prices
     *
     * @throws NoDataFoundException When either collection is empty
     */
    private function validateDataExists(
        Collection $consumptions,
        Collection $prices,
        string $startDate,
        string $endDate
    ): void {
        if ($consumptions->isEmpty() || $prices->isEmpty()) {
            throw new NoDataFoundException(
                "No existen registros de consumos o precios para el rango de fechas especificado entre {$startDate} y {$endDate}"
            );
        }
    }

    /**
     * Validate formula syntax and test with a sample value.
     *
     * @param  string  $formula  Formula to validate
     *
     * @throws InvalidFormulaException When the formula is invalid
     */
    private function validateAndTestFormula(string $formula): void
    {
        if (! str_contains($formula, '[OMIE_MD]')) {
            throw new InvalidFormulaException('La fórmula debe contener el token [OMIE_MD]');
        }

        // Test with a sample value to catch syntax errors early
        $this->evaluateFormula($formula, 100.0);
    }

    /**
     * Perform the energy cost calculation across all consumption records.
     *
     * Iterates through each consumption record and its hourly values,
     * applies the formula to each hour's price, and aggregates totals.
     *
     * @param  Collection<string, Consumption>  $consumptions  Consumption records keyed by date
     * @param  Collection<string, Price>  $prices  Price records keyed by date
     * @param  string  $formula  Mathematical formula with [OMIE_MD] token
     * @return array{price_indexed: float, total_consumption: float, total_amount: float, calculation_details: array}
     */
    private function performCalculation(
        Collection $consumptions,
        Collection $prices,
        string $formula
    ): array {
        $totalAmount = 0.0;
        $totalConsumption = 0.0;

        foreach ($consumptions as $dateKey => $consumption) {
            $price = $prices->get($dateKey);

            if (! $price) {
                continue;
            }

            for ($hour = 1; $hour <= self::HOURS_PER_DAY; $hour++) {
                $hourField = "h{$hour}";

                $hourPrice = (float) $price->$hourField;
                $hourConsumption = (float) $consumption->$hourField;

                if ($hourConsumption > 0) {
                    $calculatedPrice = $this->evaluateFormula($formula, $hourPrice);
                    $hourAmount = $calculatedPrice * $hourConsumption;

                    $totalAmount += $hourAmount;
                    $totalConsumption += $hourConsumption;
                }
            }
        }

        // Avoid division by zero
        $indexedPrice = $totalConsumption > 0
            ? $totalAmount / $totalConsumption
            : 0.0;

        return [
            'price_indexed' => round($indexedPrice, 6),
            'total_consumption' => round($totalConsumption, 3),
            'total_amount' => round($totalAmount, 2),
            'calculation_details' => [
                'days_processed' => $consumptions->count(),
                'formula_used' => $formula,
            ],
        ];
    }

    /**
     * Evaluate a formula string replacing [OMIE_MD] with the provided market price.
     *
     * Uses Symfony ExpressionLanguage for safe mathematical expression evaluation.
     *
     * @param  string  $formula  Formula with [OMIE_MD] token and arithmetic operators
     * @param  float  $marketPrice  Value to substitute for [OMIE_MD]
     * @return float The evaluated result
     *
     * @throws InvalidFormulaException When the formula contains invalid characters, has syntax errors,
     *                                 attempts division by zero, or doesn't produce a numeric result
     */
    private function evaluateFormula(string $formula, float $marketPrice): float
    {
        $evaluableFormula = str_replace('[OMIE_MD]', (string) $marketPrice, $formula);

        $this->validateFormulaCharacters($evaluableFormula);

        try {
            $result = $this->expressionLanguage->evaluate($evaluableFormula);
        } catch (SyntaxError $e) {
            throw new InvalidFormulaException('Error de sintaxis: '.$e->getMessage());
        } catch (\DivisionByZeroError $e) {
            throw new InvalidFormulaException('División por cero detectada');
        }

        if (! is_numeric($result)) {
            throw new InvalidFormulaException('La fórmula no produce un resultado numérico válido');
        }

        return (float) $result;
    }

    /**
     * Validate that the formula only contains allowed characters.
     *
     * After replacing [OMIE_MD], the formula should only contain:
     * - Numbers (0-9)
     * - Arithmetic operators (+ - * /)
     * - Parentheses ( )
     * - Decimal point (.)
     * - Whitespace
     *
     * @param  string  $formula  The formula to validate (with [OMIE_MD] already replaced)
     *
     * @throws InvalidFormulaException When the formula contains disallowed characters
     */
    private function validateFormulaCharacters(string $formula): void
    {
        // After replacing [OMIE_MD], only allow: numbers, operators, parentheses, dots, spaces
        if (! preg_match('/^[0-9+\-*\/\s().]+$/', $formula)) {
            throw new InvalidFormulaException('La fórmula contiene caracteres no permitidos');
        }
    }
}
