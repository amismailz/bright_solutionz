<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\MovementRequest;
use App\Services\API\MovementService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;


class MovementController extends Controller
{
    use ResponseTrait;

    protected MovementService $movementService;

    public function __construct(MovementService $movementService)
    {
        $this->movementService = $movementService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovementRequest $request)
    {
         return $this->movementService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
          return $this->movementService->getMovement($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
       public function getCongestionLeveOptions()
    {
       return $this->movementService->getCongestionLeveOptions();
    }
    
}
