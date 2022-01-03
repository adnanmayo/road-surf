<?php

namespace App\Repositories;

use App\Models\EquipmentStation;
use App\Models\RentalOrder;
use App\Models\Station;
use App\Models\Van;
use Exception;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\throwException;

/**
 * Class RentalOrderRepository.
 */
class RentalOrderRepository extends BaseRepository
{
    /**
     * RentalOrderRepository constructor.
     *
     * @param RentalOrder $rentalOrder
     */
    public function __construct(RentalOrder $rentalOrder)
    {
        $this->model = $rentalOrder;
    }

    /**
     * @return mixed
     */
    public function allByAuth()
    {
        return auth()->user()->rentalOrders()->get();
    }

    /**
     * @param array $data
     * @return RentalOrder|null
     */
    public function store(array $data = []): RentalOrder|null
    {
        DB::beginTransaction();

        try {
            $van = Van::find($data['van_id']);
            $station = Station::find($data['station_id']);

            $this->model->van()->associate($van);
            $van->update(['available' => 0]);

            $this->model->pickupStation()->associate($station);

            auth()->user()->rentalOrders()->save($this->model);

            foreach ($data['equipment'] as $equipment) {

                $station_equipment = $station->equipments()
                    ->where('equipment_id', $equipment['id'])
                    ->where('quantity', '>=', $equipment['quantity'])->first();

                if (empty($station_equipment)) {
                    return null;
                }

                $station_equipment->pivot->quantity = $station_equipment->pivot->quantity - $equipment['quantity'];
                $station_equipment->pivot->save();

                $this->model->equipments()
                    ->attach($station_equipment, ['quantity' => $equipment['quantity']]);
            }

        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }

        DB::commit();
        return $this->model;
    }

    /**
     * @param $id
     * @param array $data
     *
     * @return RentalOrder|null
     */
    public function update($id, array $data = []): RentalOrder|null
    {
        DB::beginTransaction();

        try {
            $rentalOrder = RentalOrder::find($id);

            if (!$rentalOrder) {
                return null;
            }

            if ($rentalOrder->completed_at) {
                return null;
            }

            $station = Station::find($data['station_id']);
            $rentalOrder->dropStation()->associate($station);
            $rentalOrder->van->station()->associate($station)->save();
            $rentalOrder->van()->update(['available' => 1]);

            $rentalOrder->update([
                'completed_at' => now(),
            ]);

            foreach ($rentalOrder->equipments as $equipment) {

                $station_equipment = $station->equipments()
                    ->where('equipment_id', $equipment->id)
                    ->first();

                if (empty($station_equipment)) {
                    $station->equipments()->attach(
                        $equipment->id,
                        ['quantity' => $equipment->pivot->quantity]
                    );
                } else {
                    $station_equipment->pivot->quantity = $station_equipment->pivot->quantity + $equipment->pivot->quantity;
                    $station_equipment->pivot->save();
                }
            }

        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }

        DB::commit();

        return $rentalOrder;
    }

}
