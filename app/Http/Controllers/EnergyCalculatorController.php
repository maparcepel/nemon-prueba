<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\CalculateRequest;
use App\Repositories\ConsumptionRepository;
use App\Repositories\PriceRepository;
use App\Services\EnergyCalculatorService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EnergyCalculatorController extends Controller
{
    public function __construct(
        private EnergyCalculatorService $calculatorService,
        private ConsumptionRepository $consumptionRepository,
        private PriceRepository $priceRepository
    ) {}

    /**
     * Handle the energy cost calculation request.
     *
     * @throws ApiException
     */
    public function calculate(CalculateRequest $request): JsonResponse
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
     * Retrieve all consumptions with their dates.
     */
    public function getConsumptions(): JsonResponse
    {
        $consumptions = $this->consumptionRepository->getAll();

        return response()->json($consumptions->values());
    }

    /**
     * Retrieve all prices with their dates.
     */
    public function getPrices(): JsonResponse
    {
        $prices = $this->priceRepository->getAll();

        return response()->json($prices->values());
    }
}
