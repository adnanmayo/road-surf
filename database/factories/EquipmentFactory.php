<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'price' => $this->faker->numberBetween(0,100),
            'name' => $this->faker->unique->randomElement([
                'portable toilets',
                'bed sheets',
                'sleeping bags',
                'camping tables',
                'chairs',
            ]),
        ];
    }

}
