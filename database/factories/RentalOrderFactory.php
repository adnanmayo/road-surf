<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'completed_at' => now(),
            'created_at' => Carbon::now()->subMonths(random_int(1,12)),
            'updated_at' => now(),
        ];
    }
}
