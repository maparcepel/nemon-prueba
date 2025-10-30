<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnergyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($day = 0; $day < 5; $day++) {
            $date = Carbon::now()->subDays($day)->format('Y-m-d');

            $priceData = ['date' => $date];
            for ($hour = 1; $hour <= 25; $hour++) {
                $priceData["h$hour"] = 0.1;
            }

            DB::table('prices')->insert(array_merge($priceData, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }

        for ($day = 0; $day < 5; $day++) {
            $date = Carbon::now()->subDays($day)->format('Y-m-d');

            $consumptionData = ['date' => $date];

            for ($hour = 1; $hour <= 25; $hour++) {
                $consumptionData["h$hour"] = 10.0;
            }

            DB::table('consumptions')->insert(array_merge($consumptionData, [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]));
        }
    }
}
