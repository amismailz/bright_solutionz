<?php

namespace App\Services\API;;

use App\Enums\CongestionLevelEnum;
use App\Enums\MovementTypeEnum;
use App\Enums\RoleTypeEnum;
use App\Http\Requests\API\MovementRequest;
use App\Http\Resources\MovementResource;
use App\Http\Resources\OptionsRangeResource;
use App\Http\Resources\PointResource;
use App\Http\Resources\RangeResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewStandardResource;
use App\Models\City;
use App\Models\Movement;
use App\Models\Point;
use App\Models\Range;
use App\Models\Review;
use App\Models\ReviewStandard;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function Pest\Laravel\call;
use function Symfony\Component\String\s;


class RangeService
{
    const SORT_DIRECTIONS = ['asc', 'desc'];
    use ResponseTrait;

    public function show($id)
    {
        try {

            $range = Range::find($id);
            if (!$range) {
                return $this->notFoundResponse('Range');
            }
            $points = $range->points;
            return $this->okResponse(
                __('Returned Range Details successfully.'),
                [
                    'range' => new RangeResource($range),
                    'points' => PointResource::collection($points),
                    'topAverageByReviewersForPoints' => $this->getTopAverageByReviewersForPoints($range->id),
                    'topAverageByVisitorsForPoints' => $this->getTopAverageByVisitorsForPoints($range->id),
                    'TopCurrentBalancePercentagePoints' => $this->getTopCurrentBalancePercentagePoints($range->id),
                    'getHourlyCurrentBalances' => $this->getHourlyCurrentBalances($range->id),
                    'getTopAverageByReviewersForPointsLatestDate' => $this->getTopAverageByReviewersForPointsLatestDate($range->id)
                ]
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            //            dd($exception);
            return $this->exceptionFailed($exception);
        }
    }

    public function getRangesOptions()
    {
        return OptionsRangeResource::collection(Range::all());
    }


    public function getCitiesOptions()
    {
        return City::pluck('name', 'id');
    }
    public function getRangesForCity($id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->unauthorized();
        }

        if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
            $city = City::find($id);

            if (!$city) {
                return $this->notFoundResponse('City');
            }

            $ranges = Range::select('*')
                ->where('city_id', $city->id)
                ->get();
            return  RangeResource::collection($ranges);
        } else {
            return $this->badRequestResponse(__('You do not have permission to view this page.'));
        }
    }


    public function getPointForRange($id)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->unauthorized();
        }

        if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
            $range = Range::find($id);

            if (!$range) {
                return $this->notFoundResponse('Range');
            }

            $points = Point::select('*')
                ->where('range_id', $range->id)
                ->get();
            return  PointResource::collection($points);
        } else {
            return $this->badRequestResponse(__('You do not have permission to view this page.'));
        }
    }

    public function updateHours($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $range = Range::find($id);
                if (!$range) {
                    return $this->notFoundResponse('Range');
                }
                return $this->okResponse(
                    __('Returned Range Details successfully.'),
                    [
                        'getHourlyCurrentBalances' => $this->getHourlyCurrentBalances($range->id),
                    ]
                );
            } else {

                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }

    public function updateMovements($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $range = Range::find($id);
                if (!$range) {
                    return $this->notFoundResponse('Range');
                }

                if (\request()->date) {
                    $startOfDay = Carbon::parse(\request()->date)->startOfDay()->toDateString();
                } else {
                    $startOfDay = Carbon::now()->toDateString();
                }


                return $this->okResponse(
                    __('Returned Range Details successfully.'),
                    [
                        'getMovementsCountByTypeAndDateAttribute' => $range->getMovementsCountByTypeAndDateAttribute($startOfDay),
                    ]
                );
            } else {

                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
    public function updateMovementsCount($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $range = Range::find($id);
                if (!$range) {
                    return $this->notFoundResponse('Range');
                }

                if (\request()->date) {
                    $startOfDay = Carbon::parse(\request()->date)->startOfDay()->toDateString();
                } else {
                    $startOfDay = Carbon::now()->toDateString();
                }

                $today = Carbon::parse($startOfDay);

                $movements = $range->points()
                    ->with(['movements' => function ($query) use ($today) {
                        $query->whereDate('created_at', $today);
                    }])
                    ->get()
                    ->flatMap->movements;

                $count =  $movements->filter(function ($movement) use ($today) {
                    return Carbon::parse($movement->created_at)->isSameDay($today);
                })->count();


                return $this->okResponse(
                    __('Returned Range Details successfully.'),
                    [
                        'getTotalMovementsCountDateAttribute' => $count,
                    ]
                );
            } else {

                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }




    public function  getAllReviewersForPoints($rangeId, $filters = [])
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }

            $perPage = $filters['per_page'] ?? 15;
            $currentPage = $filters['page'] ?? 1;
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $range = Range::find($rangeId);
                if (!$range) {
                    return $this->notFoundResponse('Range');
                }
                $sortField = $filters['sort_field'] ?? 'created_at';
                $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'asc');

                $getAllReviewersForPoint = Point::leftJoin('reviews', 'points.id', '=', 'reviews.point_id')
                    ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
                    ->select('points.*')
                    ->selectRaw('COALESCE(AVG(review_standards.rate), 0) as average_rating_reviwers')
                    ->whereNull('points.deleted_at')
                    ->where('points.range_id', $rangeId)
                    ->groupBy(
                        'points.id',
                        'points.name',
                        'points.range_id',
                        'points.association_id',
                        'points.lat',
                        'points.long',
                        'points.storage_capacity',
                        'points.current_balance',
                        'points.congestion_level',
                        'points.created_at',
                        'points.updated_at',
                        'points.deleted_at'
                    )
                    ->orderBy($sortField, $sortDirection)
                    // ->orderByDesc('average_rating_reviwers')
                    ->paginate($perPage, ['*'], 'page', $currentPage);
                return  $this->paginateResponse(PointResource::collection($getAllReviewersForPoint));
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }




    public function getReviewDetails($reviewId)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            $Review = Review::find($reviewId);
            if (!$Review) {
                return $this->notFoundResponse('Review');
            }
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $ReviewStandards = ReviewStandard::with('standard')->where('review_id', $reviewId)
                    ->get();

                return  $this->okResponse('Returned Review Details successfully', ['review' => new  ReviewResource($Review), 'standers'   => ReviewStandardResource::collection($ReviewStandards)]);
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            // dd($exception);
            return $this->exceptionFailed($exception);
        }
    }

    public function getAllAverageByReviewersForPointsLatestDate($rangeId, array $filters = [])
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            $perPage = $filters['per_page'] ?? 15;
            $currentPage = $filters['page'] ?? 1;
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $range = Range::find($rangeId);
                if (!$range) {
                    return $this->notFoundResponse('Range');
                }
                $sortField = $filters['sort_field'] ?? 'created_at';
                if ($sortField == 'average_rating_reviwers') {
                    $sortField = 'average_rating_reviwers';
                } else {
                    $sortField = 'reviews.' . $sortField;
                }
                $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'asc');
                $allAverageReview = Review::with('point', 'range')->leftJoin('points', 'points.id', '=', 'reviews.point_id')
                    ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
                    ->select('points.name',  'reviews.*')
                    ->selectRaw('COALESCE(AVG(review_standards.rate), 0) as average_rating_reviwers')
                    ->whereNull('points.deleted_at')
                    ->where('points.range_id', $rangeId)
                    ->groupBy(
                        'points.name',
                        'reviews.point_id',
                        'reviews.id',
                        'reviews.point_id',
                        'reviews.user_id',
                        'reviews.range_id',
                        'reviews.description',
                        'reviews.other',
                        'reviews.latitude',
                        'reviews.longitude',
                        'reviews.images',
                        'reviews.videos',
                        'reviews.created_at',
                        'reviews.updated_at',
                        'reviews.deleted_at'
                    )

                    ->orderBy($sortField, $sortDirection)

                    ->paginate($perPage, ['*'], 'page', $currentPage);
                return  $this->paginateResponse(ReviewResource::collection($allAverageReview));
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
    public function getAllCurrentBalancePercentagePoints($rangeId, array $filters = [])
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            $perPage = $filters['per_page'] ?? 15;
            $currentPage = $filters['page'] ?? 1;
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $range = Range::find($rangeId);
                if (!$range) {
                    return $this->notFoundResponse('Range');
                }
                $sortField = $filters['sort_field'] ?? 'current_balance';
                $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'asc');
                $allCurrentBalancePercentagePoints = Point::select('*')
                    ->where('range_id', $rangeId)
                    ->orderBy($sortField, $sortDirection)
                    ->paginate($perPage, ['*'], 'page', $currentPage);
                return  $this->paginateResponse(PointResource::collection($allCurrentBalancePercentagePoints));
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
    private function validateSortDirection($sortDirection)
    {
        return in_array($sortDirection, self::SORT_DIRECTIONS) ? $sortDirection : 'asc';
    }


    private function applySortForReviews(&$query, $sortField, $sortDirection)
    {
        $query->orderBy('reviews.' . $sortField, $sortDirection);
    }


    public function getTopAverageByVisitorsForPoints($rangeId)
    {

        $topAveragePoints = Point::leftJoin('evaluations', 'points.id', '=', 'evaluations.point_id')
            ->leftJoin('evaluation_standards', 'evaluations.id', '=', 'evaluation_standards.evaluation_id')
            ->select('points.*')
            ->selectRaw('COALESCE(AVG(evaluation_standards.rate), 0) as average_rating_visitors')
            ->whereNull('points.deleted_at')
            ->where('points.range_id', $rangeId)
            ->groupBy(
                'points.id',
                'points.name',
                'points.range_id',
                'points.association_id',
                'points.lat',
                'points.long',
                'points.storage_capacity',
                'points.current_balance',
                'points.congestion_level',
                'points.created_at',
                'points.updated_at',
                'points.deleted_at'
            )
            ->orderByDesc('average_rating_visitors')
            ->limit(5)
            ->get();
        return  PointResource::collection($topAveragePoints);
    }
    public function getTopCurrentBalancePercentagePoints($rangeId, $filters = [])
    {
        $sortField = $filters['sort_field'] ?? 'current_balance';
        $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'asc');
        $topCurrentBalancePercentagePoints = Point::select('*')
            ->where('range_id', $rangeId)
            ->orderBy($sortField, $sortDirection)
            ->limit(5)
            ->get();
        return  PointResource::collection($topCurrentBalancePercentagePoints);
    }

    public function getTopAverageByReviewersForPointsLatestDate($rangeId, $filters = [])
    {
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'desc');
        if ($sortField == 'average_rating_reviwers') {
            $sortField = 'average_rating_reviwers';
        } else {
            $sortField = 'reviews.' . $sortField;
        }
        $topAverageReview = Review::with('point', 'range')->leftJoin('points', 'points.id', '=', 'reviews.point_id')
            ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
            ->select('points.name', 'points.id', 'reviews.*')
            ->selectRaw('COALESCE(AVG(review_standards.rate), 0) as average_rating_reviwers')
            ->whereNull('points.deleted_at')
            ->where('points.range_id', $rangeId)
            ->groupBy(
                'points.name',
                'reviews.point_id',
                'reviews.id',
                'reviews.point_id',
                'reviews.user_id',
                'reviews.range_id',
                'reviews.description',
                'reviews.other',
                'reviews.latitude',
                'reviews.longitude',
                'reviews.images',
                'reviews.videos',
                'reviews.created_at',
                'reviews.updated_at',
                'reviews.deleted_at'
            )
            ->orderBy($sortField, $sortDirection)
            // ->orderByDesc('reviews.created_at')
            ->limit(5)
            ->get();

        return ReviewResource::collection($topAverageReview);
    }
    public function getTopAverageByReviewersForPoints($rangeId, $filters = [])
    {
        $sortField = $filters['sort_field'] ?? 'average_rating_reviwers';
        $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'desc');
        $topAverageReviewPoints = Point::leftJoin('reviews', 'points.id', '=', 'reviews.point_id')
            ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
            ->select('points.*')
            ->selectRaw('COALESCE(AVG(review_standards.rate), 0) as average_rating_reviwers')
            ->whereNull('points.deleted_at')
            ->where('points.range_id', $rangeId)
            ->groupBy(
                'points.id',
                'points.name',
                'points.range_id',
                'points.association_id',
                'points.lat',
                'points.long',
                'points.storage_capacity',
                'points.current_balance',
                'points.congestion_level',
                'points.created_at',
                'points.updated_at',
                'points.deleted_at'
            )
            ->orderBy($sortField, $sortDirection)
            ->limit(5)
            ->get();

        return PointResource::collection($topAverageReviewPoints);
    }

    private function getHourlyCurrentBalances($rangeId): array
    {
        //  dd(request()->hourly_date);
        $points = Range::findOrFail($rangeId)->points(['id', 'storage_capacity']);
        $pointIds = $points->pluck('id');
        $totalStorageCapacity = $points->sum('storage_capacity');

        if ($totalStorageCapacity === 0) {
            return [];
        }

        if (\request()->hourly_date) {
            $startOfDay = Carbon::parse(\request()->hourly_date)->startOfDay();
        } else {
            $startOfDay = now()->startOfDay();
        }


        $now = now();
        $currentHourLabel = $now->format('H:00');

        // Get initial deposit & exchange before today
        $initial = Movement::whereIn('point_id', $pointIds)
            ->where('created_at', '<', $startOfDay)
            ->selectRaw("
            SUM(CASE WHEN type = 'deposit' THEN quantity ELSE 0 END) AS initial_deposit,
            SUM(CASE WHEN type = 'exchange' THEN quantity ELSE 0 END) AS initial_exchange
        ")
            ->first();

        $initialDeposit = $initial->initial_deposit ?? 0;
        $initialExchange = $initial->initial_exchange ?? 0;

        $runningDeposit = 0;
        $runningExchange = 0;
        $runningBalance = 0;

        // Today's hourly changes
        $hourlyRaw = Movement::whereIn('point_id', $pointIds)
            ->whereDate('created_at', $startOfDay)
            ->selectRaw("
            DATE_FORMAT(created_at, '%H:00') AS hour_key,
            SUM(CASE WHEN type = 'deposit' THEN quantity ELSE 0 END) AS deposit,
            SUM(CASE WHEN type = 'exchange' THEN quantity ELSE 0 END) AS exchange
        ")
            ->groupBy('hour_key')
            ->orderBy('hour_key')
            ->get()
            ->keyBy('hour_key');

        $hourlyData = [];

        for ($hour = 0; $hour < 24; $hour++) {
            $hourLabel = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';

            if ($hourLabel > $currentHourLabel && $startOfDay->toDateString() == $now->toDateString()) {
                break;
            }

            $deposit = $hourlyRaw[$hourLabel]->deposit ?? 0;
            $exchange = $hourlyRaw[$hourLabel]->exchange ?? 0;

            $runningDeposit += $deposit;
            $runningExchange += $exchange;
            $runningBalance += ($deposit - $exchange);

            $hourlyData[] = [
                'hour'     => $hourLabel,
                'deposit'  => $runningDeposit,
                'exchange' => $runningExchange,
                'balance'  => $runningBalance,
                'percent'  => round(($runningBalance / $totalStorageCapacity) * 100, 0),
            ];
        }

        return $hourlyData;
    }
}
