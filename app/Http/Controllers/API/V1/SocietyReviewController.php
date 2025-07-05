<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SocietyReviewRequest;
use App\Services\API\SocietyReviewService;
use App\Traits\ResponseTrait;


class SocietyReviewController extends Controller
{
    use ResponseTrait;

    protected SocietyReviewService $societyReviewService;

    public function __construct(SocietyReviewService $societyReviewService)
    {
        $this->societyReviewService = $societyReviewService;
    }

    public function store(SocietyReviewRequest $request)
    {
         return $this->societyReviewService->store($request);
    }

    public function options()
    {
        return $this->societyReviewService->getOptions();
    }

}
