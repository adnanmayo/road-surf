<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRentalOrderRequest;
use App\Http\Requests\UpdateRentalOrderRequest;
use App\Models\RentalOrder;
use App\Repositories\RentalOrderRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RentalOrderController extends Controller
{
    /**
     * @var RentalOrderRepository
     */
    protected $rentalOrderRepository;

    /**
     * RentalOrderController constructor.
     *
     * @param RentalOrderRepository $rentalOrderRepository
     */
    public function __construct(RentalOrderRepository $rentalOrderRepository)
    {
        $this->rentalOrderRepository = $rentalOrderRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $response = $this->rentalOrderRepository->allByAuth();

        if ($response->isEmpty()) {
            return response()->json(['message' => 'No Record Found'], 401);
        }

        return response()->json($response, 201);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param StoreRentalOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreRentalOrderRequest $request)
    {
        $response = $this->rentalOrderRepository->store($request->all());

        if ($response) {
            return response()->json($response, 201);
        }

        return response()->json(['message' => 'Bad credentials'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param RentalOrder $rentalOrder
     * @return Response
     */
    public function show(RentalOrder $rentalOrder)
    {
        return response()->json($rentalOrder, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRentalOrderRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(UpdateRentalOrderRequest $request, $id)
    {
        $response = $this->rentalOrderRepository->update($id, $request->all());

        if ($response) {
            return response()->json($response, 201);
        }

        return response()->json(['message' => 'Bad credentials'], 401);
    }
}
