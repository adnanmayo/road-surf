<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EquipmentRentalOrder;
use App\Models\RentalOrder;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $response = DB::select("
                SELECT stations.id AS station_id,
                        stations.name AS station_name,
                        equipments.id AS equipments_id,
                        equipments.name AS equipments_name,
                        equipment_stations.quantity AS station_current_stock,
                        SUM(equipment_rental_orders.quantity) AS expected_demand

                FROM stations

                LEFT JOIN equipment_stations
                    ON equipment_stations.station_id = stations.id

                INNER JOIN rental_orders
                    ON rental_orders.pickup_station_id = stations.id
                    AND DATE(rental_orders.created_at) = DATE(DATE_SUB(CURDATE(), INTERVAL 1 YEAR))

                LEFT JOIN equipment_rental_orders
                    ON equipment_rental_orders.rental_order_id = rental_orders.id

                LEFT JOIN equipments
                    ON equipments.id = equipment_rental_orders.equipment_id

                GROUP BY stations.id,
                        DATE(rental_orders.created_at),
                        equipment_rental_orders.equipment_id

                ORDER BY DATE(rental_orders.created_at)
                ");

        return response()->json($response);
    }
}
