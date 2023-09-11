<?php

namespace App\Http\Controllers;

use App\Repository\EntrypointRepositoryInterface;
use App\Http\Resources\EntrypointResource;
use App\Http\Requests\EntrypointRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EntrypointController extends Controller
{

    public function __construct(protected EntrypointRepositoryInterface $repo){}

    /**
     * Display a listing of the resource.
     */
    public function index() : ResourceCollection
    {
        return EntrypointResource::collection($this->repo->all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntrypointRequest $request) : JsonResponse
    {
        $data = $request->validated();
        $entryPoint = $this->repo->create(['name'=> $data['name']]);
        $entryPoint->slots()->attach($data['slots']);
        return  response()->json([
            'message' => 'Resource created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return new EntrypointResource($this->repo->findById($id));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id) : JsonResponse
    {
        if ($this->repo->countAll() <= 3) {
            return  response()->json([
                'message' => 'Minimum 3 entry points are required.'
            ], 500);
        }

        $this->repo->deleteById($id);
        return  response()->json([
            'message' => 'Resource deleted successfully'
        ], 200);
    }
}
