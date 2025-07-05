<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\API\EvaluationRequest;
use App\Services\API\EvaluationService;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;


class EvaluationController extends Controller
{
    use ResponseTrait;

    protected EvaluationService $evaluationService;

    public function __construct(EvaluationService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
    }

    public function store(EvaluationRequest $request)
    {
         return $this->evaluationService->store($request);
    }

    public function options()
    {
        return $this->evaluationService->getOptions();
    }

}
