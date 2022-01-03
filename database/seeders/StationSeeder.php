<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\Station;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $equipments = Equipment::factory()
            ->count(5)
            ->create();

        Station::factory()
            ->count(5)
            ->hasVans(20)
            ->afterCreating(function (Station $station) use ($equipments) {
                $station->equipments()->attach(
                    $equipments->random(rand(1, 5))->pluck('id')->toArray(),
                    ['quantity' => 20]
                );
            })
            ->create();

    }
}
