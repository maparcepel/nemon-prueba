<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateRequest;
use App\Services\EnergyCalculatorService;

class EnergyCalculatorController extends Controller
{
    public function __construct(private EnergyCalculatorService $calculatorService) {}

    /**
     * Handle the energy cost calculation request.
     */
    public function calculate(CalculateRequest $request)
    {
        try {
            $result = $this->calculatorService->calculateIndexedPrice(
                $request->validated()
            );

            return response()->json($result);

        } catch (Exception $e) {
            if (! $e instanceof ApiException) {
                Log::error('Calculation error: '.$e->getMessage());

                return response()->json(['error' => 'Error interno'], 500);
            }

            throw $e;
        }
    }

    /**
     * Retrieve all consumptions.
     */
    public function getConsumptions(): array {}

    /**
     * Retrieve all prices.
     */
    public function getPrices(): array
    {
        // TODO
    }
}
