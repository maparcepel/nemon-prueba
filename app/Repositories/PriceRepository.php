<?php

namespace App\Repositories;

use App\Models\Price;
use Illuminate\Support\Collection;

class PriceRepository
{
    /**
     * Get prices within a date range, keyed by date string.
     *
     * @param  string  $startDate  Start date in Y-m-d format
     * @param  string  $endDate  End date in Y-m-d format
     * @return Collection<string, Price>
     */
    public function getByDateRange(string $startDate, string $endDate): Collection
    {
        return Price::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->get()
            ->keyBy(fn ($price) => (string) $price->date);
    }
}
