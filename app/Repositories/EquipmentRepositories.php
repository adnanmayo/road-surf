<?php

namespace App\Repositories;


use App\Models\Equipment;
use App\Models\EquipmentRentalOrder;
use App\Models\RentalOrder;
use App\Models\Station;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class EquipmentRepositories extends BaseRepository
{

    public function __construct(RentalOrder $rentalOrder)
    {
        $this->model = $rentalOrder;
    }

    public function demand($date)
    {

        $date = CarbonImmutable::parse($date);
        $rentalOrders = RentalOrder::query()
            ->select(
                'rental_orders.pickup_station_id',
                'equipment_rental_orders.equipment_id',
                DB::raw('SUM(quantity) as total_ordered')
            )
            ->join(
                'equipment_rental_orders',
                'rental_order_id',
                '=',
                'rental_orders.id'
            )
            ->whereDate('rental_orders.created_at', '=', $date)
            ->groupByRaw('pickup_station_id,equipment_id')
            ->get();

        return $this->pastOrderData(
            collect($rentalOrders->toArray()),
            $date
        );
    }


    private function pastOrderData($rentalOrders, $date)
    {
        $data = $rentalOrders->map(function ($item) use ($date) {

            $lastYearDateStart = $date->subYear();
            $lastYearDateEnd = $lastYearDateStart->addDays(15);

            $rentalOrder = RentalOrder::query()
                ->select(
                    DB::raw('SUM(quantity)/15 as expected')
                )
                ->join(
                    'equipment_rental_orders',
                    'rental_order_id',
                    '=',
                    'rental_orders.id'
                )
                ->where('pickup_station_id', '=', $item['pickup_station_id'])
                ->where('equipment_id', '=', $item['equipment_id'])
                ->whereRaw(
                    "DATE(rental_orders.created_at) >= '" . $lastYearDateStart->format('Y-m-d') . "'" .
                    "AND DATE(rental_orders.created_at) <= '" . $lastYearDateEnd->format('Y-m-d') . "' ")
                ->first();
            $item['expected'] = $rentalOrder->expected ?? null;
            return $item;
        });

        return $data;
    }


}
