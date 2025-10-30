<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidFormulaException;
use App\Exceptions\NoDataFoundException;
use App\Http\Requests\CalculateRequest;
use App\Http\Services\EnergyCalculatorService;

class EnergyCalculatorController extends Controller
{
    public function __construct(private EnergyCalculatorService $calculatorService) {}

    /**
     * Handle the energy cost calculation request.
     */
    public function calculate(CalculateRequest $request)
    {
        return $request;
        try {
            $result = $this->calculatorService->calculateIndexedPrice(
                $request->validated()
            );

            return response()->json($result);

        } catch (NoDataFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        } catch (InvalidFormulaException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            Log::error('Calculation error: '.$e->getMessage());

            return response()->json(['error' => 'Error interno'], 500);
        }
    }

    public function getConsumptions(): array
    {
        // TODO
    }

    public function getPrices(): array
    {
        // TODO
    }
}
