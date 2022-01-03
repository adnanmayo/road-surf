<?php

namespace Database\Seeders;

use App\Models\RentalOrder;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Seeder;

class RentalOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = Station::all();
        $users = User::all();

        RentalOrder::factory()
            ->count(500)
            ->afterCreating(function (RentalOrder $order) use ($users, $stations) {

                $order->user()->associate($users->random())->save();

                $pickupStation = $stations->random();
                $order->pickupStation()->associate($pickupStation)->save();
                $order->van()->associate($pickupStation->vans->random())->save();
                $order->equipments()->attach(
                    $pickupStation->equipments
                        ->random(rand(1, $pickupStation->equipments->count()))
                        ->pluck('id')->toArray(),
                    [
                        'quantity' => rand(1, 10),
                        'created_at' => $order->created_at,
                        'updated_at' => $order->updated_at
                    ]
                );

                $dropStation = $stations->random();
                $order->created_at = now()->subYears(1);
//                $order->setCreatedAt(now()->subYears(1), '1 days');
                $order->dropStation()->associate($dropStation)->save();

            })
            ->create();
    }
}
