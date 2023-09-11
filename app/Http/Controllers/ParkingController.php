<?php

namespace App\Http\Controllers;

use App\Repository\ParkingRepositoryInterface;
use App\Service\Parking\ParkingServiceInterface;
use App\Http\Resources\ParkingResource;
use App\Http\Requests\ParkingRequest;
use Illuminate\Http\JsonResponse;

class ParkingController extends Controller
{

    public function __construct(
        protected ParkingRepositoryInterface $repo,
        protected ParkingServiceInterface $service
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ParkingResource::collection($this->repo->all());
    }

    public function park(ParkingRequest $request) : JsonResponse
    {
        $parkingData = $request->validated();
        $this->service->park($parkingData);
        return  response()->json('Vehicle parking registered successfully.', 201);
    }

    public function unpark(int $id) : JsonResponse
    {
        $this->service->unpark($id);
        return  response()->json('Vehicle unparked successfully.', 200);
    }
}
