<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RentalOrderController;
use App\Http\Controllers\Api\StationController;
use App\Http\Controllers\Api\HomeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/equipments/orders', [\App\Http\Controllers\Api\EquipmentController::class, 'orders']);
Route::get('/equipments/available', [\App\Http\Controllers\Api\EquipmentController::class, 'available']);
Route::get('/equipments/demand', [\App\Http\Controllers\Api\EquipmentController::class, 'demand']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
