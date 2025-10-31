<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateRequest;
use App\Repositories\ConsumptionRepository;
use App\Repositories\PriceRepository;
use App\Services\EnergyCalculatorService;

class EnergyCalculatorController extends Controller
{
    public function __construct(
        private EnergyCalculatorService $calculatorService,
        private ConsumptionRepository $consumptionRepository,
        private PriceRepository $priceRepository
    ) {}

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
     *
     * @return array<string, Consumption>
     */
    public function getConsumptions(): array
    {
        return $this->consumptionRepository->getAll()->toArray();
    }

    /**
     * Retrieve all prices.
     *
     * @return array<string, Price>
     */
    public function getPrices(): array
    {
        return $this->priceRepository->getAll()->toArray();
    }
}
