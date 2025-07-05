<?php

namespace App\Http\Controllers\API\V1;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\API\MovementRequest;
use App\Services\API\MovementService;
use App\Services\API\RangeService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;


class RangeController extends Controller
{
    use ResponseTrait;

    protected RangeService $rangeService;

    public function __construct(RangeService $rangeService)
    {
        $this->rangeService = $rangeService;
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
    public function store(MovementRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->unauthorized();
        }

        if ($user->getRole()  != RoleTypeEnum::AdminSupervisor->value) {
            return $this->badRequestResponse(__('You do not have permission to view this page.'));
        }

        return $this->rangeService->show($id);
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
    public function getRangesOptions()
    {
        return $this->rangeService->getRangesOptions();
    }
    public function getCitiesOptions()
    {
        return $this->rangeService->getCitiesOptions();
    }
    public function getRangesForCity($id)
    {
        return $this->rangeService->getRangesForCity($id);
    }

    public function getPointForRange($id)
    {
        return $this->rangeService->getPointForRange($id);
    }


    public function update_movements($id)
    {
        return $this->rangeService->updateMovements($id);
    }

    public function update_movements_count($id)
    {
        return $this->rangeService->updateMovementsCount($id);
    }

    public function update_hours($id)
    {
        return $this->rangeService->updateHours($id);
    }
    public function getAllAverageByReviewersForPointsLatestDate($rangeId, Request $request)
    {
        $filters = $request->all();
        return $this->rangeService->getAllAverageByReviewersForPointsLatestDate($rangeId, $filters);
    }
    public function getReviewDetails($id)
    {
        return $this->rangeService->getReviewDetails($id);
    }
    public function getAllReviewersForPoints($id, Request $request)
    {
        $filters = $request->all();
        return $this->rangeService->getAllReviewersForPoints($id, $filters);
    }
    public function getAllCurrentBalancePercentagePoints($id, Request $request)
    {
        $filters = $request->all();
        return $this->rangeService->getAllCurrentBalancePercentagePoints($id, $filters);
    }

    public function getTopCurrentBalancePercentagePoints($id, Request $request)
    {
        $filters = $request->all();
        return $this->rangeService->getTopCurrentBalancePercentagePoints($id, $filters);
    }
    public function getTopAverageByReviewersForPointsLatestDate($id, Request $request)
    {
        $filters = $request->all();
        return $this->rangeService->getTopAverageByReviewersForPointsLatestDate($id, $filters);
    }
    public function getTopAverageByReviewersForPoints($id, Request $request)
    {
        $filters = $request->all();
        return $this->rangeService->getTopAverageByReviewersForPoints($id, $filters);
    }
}
