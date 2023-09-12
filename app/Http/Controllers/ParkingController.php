<?php

namespace App\Http\Controllers;

use App\Repository\ParkingRepositoryInterface;
use App\Service\Parking\ParkingServiceInterface;
use App\Http\Resources\ParkingResource;
use App\Http\Requests\ParkingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ParkingController extends Controller
{

    public function __construct(
        protected ParkingRepositoryInterface $repo,
        protected ParkingServiceInterface $service
    ){}

    /**
     * Display a listing of the resource.
     */
    public function index() : ResourceCollection
    {
        return ParkingResource::collection($this->repo->all());
    }

    public function park(ParkingRequest $request) : JsonResponse
    {
        $parking = $this->service->park($request->validated());
        return  response()->json($parking, 201);
    }

    public function unpark(int $id) : JsonResponse
    {
        $parking = $this->service->unpark($id);
        return  response()->json($parking, 200);
    }
}
