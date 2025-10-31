<?php

namespace App\Repositories;

use App\Models\Consumption;
use Illuminate\Support\Collection;

class ConsumptionRepository
{
    /**
     * Get consumptions within a date range, keyed by date string.
     *
     * @param  string  $startDate  Start date in Y-m-d format
     * @param  string  $endDate  End date in Y-m-d format
     * @return Collection<string, Consumption>
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return Consumption::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($consumption) => (string) $consumption->date);
    }

    /**
     * Retrieve all consumption records.
     *
     * @return Collection<string, Consumption>
     */
    public function getAll(): Collection
    {
        return Consumption::orderBy('date')->get()->keyBy(fn ($consumption) => (string) $consumption->date);
    }
}
