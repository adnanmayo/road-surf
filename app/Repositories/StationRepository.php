<?php

namespace App\Repositories;

use App\Models\Station;
use App\Models\Van;
use Exception;
use Illuminate\Support\Facades\DB;

/**
 * Class StationRepository.
 */
class StationRepository extends BaseRepository
{
    /**
     * StationRepository constructor.
     *
     * @param Station $station
     */
    public function __construct(Station $station)
    {
        $this->model = $station;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function allWithRelations()
    {
        return $this->model->with(['vans', 'equipments'])->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findWithRelations($id)
    {
        return $this->model->where('id', $id)->with(['vans', 'equipments'])->get();
    }

}
