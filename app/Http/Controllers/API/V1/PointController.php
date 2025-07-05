<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\API\MovementRequest;
use App\Services\API\MovementService;
use App\Services\API\PointService;
use App\Services\API\RangeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;


class PointController extends Controller
{
    use ResponseTrait;

    protected PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }
    public function getPointBySlug(string $slug)
    {
        return $this->pointService->getPointBySlug($slug);
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {}

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
    public function store(MovementRequest $request) {}

    /**
     * Display the specified resource.
     */
    public function show(Request $request,  $id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->unauthorized();
        }

        if ($user->getRole()  != RoleTypeEnum::AdminSupervisor->value) {
            return $this->badRequestResponse(__('You do not have permission to view this page.'));
        }
        return $this->pointService->show($request, $id);
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

    public function getAllReviews(string $slug, Request $request)
    {
        $filters = $request->all();
        return $this->pointService->getAllReviews($slug, $filters);
    }
    public function getAllMovements($id, Request $request)
    {
        $filters = $request->all();
        return $this->pointService->getAllMovements($id, $filters);
    }
    public function getMovementsForPoint($rangeId, Request $request)
    {
        $filters = $request->all();
        return $this->pointService->getMovementsForPoint($rangeId, $filters);
    }

    public function getReviewersForPointWithSort($rangeId, Request $request)
    {
        $filters = $request->all();
        return $this->pointService->getReviewersForPointWithSort($rangeId, $filters);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getPointsOptions()
    {
        return $this->pointService->getPointsOptions();
    }
    public function getPointsSlugOptions()
    {
        return $this->pointService->getPointsSlugOptions();
    }
    public function getPoint($id)
    {
        return $this->pointService->getPoint($id);
    }

    public function update_hours($id)
    {
        return $this->pointService->updateHours($id);
    }

    public function update_movements($id)
    {
        return $this->pointService->updateMovements($id);
    }
    public function getAllMedia($id)
    {
        return $this->pointService->getAllMedia($id);
    }
}
