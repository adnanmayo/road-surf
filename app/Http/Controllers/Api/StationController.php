<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Repositories\StationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StationController extends Controller
{
    /**
     * @var StationRepository
     */
    protected $stationRepository;

    /**
     * StationController constructor.
     *
     * @param StationRepository $stationRepository
     */
    public function __construct(StationRepository $stationRepository)
    {
        $this->stationRepository = $stationRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $collection = $this->stationRepository->allWithRelations();

        if ($collection->isEmpty()) {
            return response()->json(['message' => 'No Record Found'], 401);
        }

        return response()->json($collection, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $collention = $this->stationRepository->findWithRelations($id);

        if ($collention->isEmpty()) {
            return response()->json(['message' => 'No Record Found'], 401);
        }

        return response()->json($collention, 201);
    }

}
