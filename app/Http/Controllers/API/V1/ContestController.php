<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\ContestRequest;
use App\Services\API\ContestService;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;


class ContestController extends Controller
{
    use ResponseTrait;

    protected ContestService $contestService;

    public function __construct(ContestService $contestService)
    {
        $this->contestService = $contestService;
    }

    public function store(ContestRequest $request)
    {
         return $this->contestService->store($request);
    }

    public function options()
    {
        return $this->contestService->getOptions();
    }

}
