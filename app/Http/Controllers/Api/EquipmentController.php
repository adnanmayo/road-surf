<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Repositories\EquipmentRepositories;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public $equipmentRepositories;

    public function __construct(EquipmentRepositories $equipmentRepositories)
    {
        $this->equipmentRepositories =  $equipmentRepositories;

    }
    //
//    public function orders(Request $request)
//    {
//        return $this->equipmentRepositories->ordersByRange(
//            Carbon::parse($request->start_date ),
//            Carbon::parse($request->end_date)
//        );
//    }

//    public function available(Request $request)
//    {
//        return $this->equipmentRepositories->available(
//            Carbon::parse($request->date ),
//        );
//    }
    public function demand(Request $request)
    {
        return $this->equipmentRepositories->demand(
            Carbon::parse($request->date ),
        );
    }
}
