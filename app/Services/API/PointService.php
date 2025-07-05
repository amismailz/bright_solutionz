<?php

namespace App\Services\API;;

use App\Enums\CongestionLevelEnum;
use App\Enums\MovementTypeEnum;
use App\Enums\RoleTypeEnum;
use App\Http\Requests\API\MovementRequest;
use App\Http\Resources\MediaResource;
use App\Http\Resources\MediaReviewResource;
use App\Http\Resources\MovementResource;
use App\Http\Resources\PointResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\UserResource;
use App\Models\Movement;
use App\Models\Point;
use App\Models\Range;
use App\Models\Review;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PointService
{

    use ResponseTrait;
    const SORT_DIRECTIONS = ['asc', 'desc'];
    public function getPoint($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }

            $point = Point::find($id);
            if (!$point) {
                return $this->notFoundResponse('Point');
            }
            return $this->okResponse(
                __('Returned Point Details successfully.'),
                [
                    'points' => new PointResource($point),

                ]
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            dd($exception);
            return $this->exceptionFailed($exception);
        }
    }

    public function getAllReviews($pointId, array $filters = [])
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }

            $perPage = $filters['per_page'] ?? 15;
            $currentPage = $filters['page'] ?? 1;

            if ($user->getRole() === RoleTypeEnum::AdminSupervisor->value) {
                $point = Point::find($pointId);
                if (!$point) {
                    return $this->notFoundResponse('Point');
                }

                $sortField = $filters['sort_field'] ?? 'average_rating_reviwers';
                $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'desc');
                if ($sortField == 'average_rating_reviwers') {
                    $sortField = 'average_rating_reviwers';
                } else {
                    $sortField = 'reviews.' . $sortField;
                }
                // build the query before pagination
                $query = Review::with('point', 'range', 'user')
                    ->leftJoin('points', 'points.id', '=', 'reviews.point_id')
                    ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
                    ->select('points.name', 'reviews.*', \DB::raw('AVG(review_standards.rate) as average_rating_reviwers'))

                    ->whereNull('points.deleted_at')
                    ->where('points.id', $pointId)
                    ->groupBy(
                        'points.name',
                        'reviews.point_id',
                        'reviews.id',
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
                    ->orderBy($sortField, $sortDirection); // Apply sorting directly

                // Paginate after building the full query
                $allAverageReview = $query->paginate($perPage, ['*'], 'page', $currentPage);

                return $this->paginateResponse(ReviewResource::collection($allAverageReview));
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            dd($exception);
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
    private function applySortForMovements(&$query, $sortField, $sortDirection)
    {
        $query->orderBy('movements.' . $sortField, $sortDirection);
    }
    public function show(Request $request, $id)
    {
        try {
            $point = Point::with('reviews')->find($id);
            if (!$point) {
                return $this->notFoundResponse('Point');
            }
            $date = Carbon::parse($request->input('date', now()));
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();


            $movements = Movement::with('user')->where('point_id', $point->id)
                ->orderByDesc('created_at')
                ->whereBetween('movements.created_at', [$start, $end])
                ->limit(10)
                ->get();
       
            return $this->okResponse(
                __('Returned Point Details successfully.'),
                [
                    'points' => new PointResource($point),
                    'movements' => MovementResource::collection($movements),
                    'getHourlyCurrentBalancesByPoint' => $this->getHourlyCurrentBalancesByPoint($point->id, $date),
                    'getHourlyCongestionLevelByPoint' => $this->getHourlyCongestionLevelByPoint($point->id),
                    'getReviewersForPoint' => $this->getReviewersForPoint($point->id, $date),
                    'getMedia' => $this->getRandomMedia($point->id),
                    'count_media' => $this->MediaCount($point),
                    'current_balance_by_date' => $point->getCurrentBalanceByDate($date),
                    'storage_usage_percentage_by_date' => $point->getStorageUsagePercentageByDate($date),
                    'congestion_level_by_date' => $point->getCongestionLevelByDate($date),
                    'average_rating_reviewers_by_date'  => $point->getAverageRatingReviewersByDate($date),
                    'getMovementsCountByTypeAndDate' => $point->getMovementsCountByTypeAndDate($date),
                ]
            );
        } catch (\Exception $exception) {
            dd($exception);

            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
       private function MediaCount($point)
    {
         $reviewMediaCount = $point->reviews->reduce(function ($carry, $review) {
                $images = $videos =[];

                if (!empty($review->images)) {
                    if (is_array($review->images)) {
                        $images = $review->images;
                    } elseif (is_string($review->images)) {
                        $decoded = json_decode($review->images, true);
                        $images = is_array($decoded)
                            ? $decoded
                            : explode(',', $review->images); // fallback
                    }
                }
                if (!empty($review->videos)) {
                    if (is_array($review->videos)) {
                        $videos = $review->videos;
                    } elseif (is_string($review->videos)) {
                        $decoded = json_decode($review->videos, true);
                        $videos = is_array($decoded) ? $decoded : explode(',', $review->videos);
                    }
                }

                return $carry + count($images);
            }, 0);

            return ($point->getMedia('images')->count() ?? 0) + ($point->getMedia('videos')->count() ?? 0) + ($reviewMediaCount ?? 0);
    }

    public function getPointBySlug($slug)
    {

        try {

            $point = Point::where('slug', $slug)->first();
            if (!$point) {
                return $this->notFoundResponse('Point');
            }
            return $this->okResponse(
                __('Returned Point Details successfully.'),
                [
                    'points' => new PointResource($point),
                ]
            );
        } catch (\Exception $exception) {
            dd($exception);

            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
    public function getAllMedia($id)
    {
        try {
            $point = Point::find($id);
            if (!$point) {
                return $this->notFoundResponse('Point');
            }
            $reviews = Review::where('point_id', $id)->get();
            $visualDocumentationImages = $point->getMedia('images');
            $visualDocumentationVideos = $point->getMedia('videos');
            $reviewImages = collect();
            $reviewVideos = collect();
            foreach ($reviews as $review) {
                $images = [];
                $videos = [];
                if (!empty($review->images)) {
                    if (is_array($review->images)) {
                        $images = $review->images;
                    } elseif (is_string($review->images)) {
                        $decoded = json_decode($review->images, true);
                        $images = is_array($decoded) ? $decoded : explode(',', $review->images);
                    }
                }
                // Handle videos
                if (!empty($review->videos)) {
                    if (is_array($review->videos)) {
                        $videos = $review->videos;
                    } elseif (is_string($review->videos)) {
                        $decoded = json_decode($review->videos, true);
                        $videos = is_array($decoded) ? $decoded : explode(',', $review->videos);
                    }
                }

                $reviewImages = $reviewImages->merge(
                    collect($images)->map(fn($img) => [
                        'url' => route('download.secure.file', ['filename' => ltrim($img, '/')]),
                        // 'source' => 'review',
                    ])
                );
                $reviewVideos = $reviewVideos->merge(
                    collect($videos)->map(fn($video) => [
                        'url' => route('download.secure.file', ['filename' => ltrim($video, '/')]),
                        // 'source' => 'review',
                    ])
                );
            }
            return $this->okResponse(
                __('Returned Media successfully.'),
                [
                    'visualDocumentationImages' =>  MediaResource::collection($visualDocumentationImages),
                    'reviewImages' => $reviewImages,
                    'visualDocumentationVideos' =>  MediaResource::collection($visualDocumentationVideos),
                    'reviewVideos' => $reviewVideos,
                ]
            );
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            dd($exception);
            return $this->exceptionFailed($exception);
        }
    }
    public function getAllMovements($pointId, array $filters = [])
    {
        try {

            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            $perPage = $filters['per_page'] ?? 15;
            $currentPage = $filters['page'] ?? 1;
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $point = Point::find($pointId);
                if (!$point) {
                    return $this->notFoundResponse('Point');
                }
                $sortField = $filters['sort_field'] ?? 'quantity';
                $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'asc');

                $movementsQuery = Movement::with('user')
                    ->where('point_id', $point->id);

                $this->applySortForMovements($movementsQuery, $sortField, $sortDirection);

                $movements = $movementsQuery->paginate($perPage, ['*'], 'page', $currentPage);
                return  $this->paginateResponse(MovementResource::collection($movements));
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            dd($exception);
            return $this->exceptionFailed($exception);
        }
    }
    public function getRandomMedia($id)
    {
        try {
            $point = Point::find($id);
            if (!$point) {
                return $this->notFoundResponse('Point not found');
            }

            $reviews = Review::where('point_id', $id)->get();

            // ✅ Get latest point images
            $pointImages = collect(
                $point->getMedia('images')
                    ->sortByDesc('created_at')
                    ->map(fn($media) => [
                        'url' => $media->getFullUrl(),
                        'thumbnail' => $media->getFullUrl('thumb'),
                        'source' => 'admin',
                        'created_at' => $media->created_at,
                    ])
            );

            // ✅ Collect review images
            $reviewImages = collect();
            $allImages = $pointImages
                ->merge($reviewImages)
                ->sortByDesc('created_at')
                ->take(4)
                ->values();
            foreach ($reviews as $review) {
                $images = [];

                if (!empty($review->images)) {
                    if (is_array($review->images)) {
                        $images = $review->images;
                    } elseif (is_string($review->images)) {
                        $decoded = json_decode($review->images, true);
                        $images = is_array($decoded) ? $decoded : explode(',', $review->images);
                    }
                }



                $reviewImages = $reviewImages->merge(
                    collect($images)->map(fn($img) => [
                        'url' => route('download.secure.file', ['filename' => ltrim($img, '/')]),
                        'thumbnail' => route('download.secure.file', ['filename' => ltrim($img, '/')]),
                        'source' => 'review',
                        'created_at' => $review->created_at,
                    ])
                );
            }

            // ✅ Merge and sort all by created_at desc (latest first)
            $allImages = $pointImages
                ->merge($reviewImages)
                ->sortByDesc('created_at')
                ->take(4)
                ->values();

            return $allImages;
        } catch (\Exception $exception) {
            //dd($exception);
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }





    public function getPointsOptions()
    {
        return Point::pluck('name', 'id');
    }
    public function getPointsSlugOptions()
    {
        return Point::pluck('slug');
    }

    public function updateHours($id)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $point = Point::find($id);
                if (!$point) {
                    return $this->notFoundResponse('Point');
                }
                return $this->okResponse(
                    __('Returned Point Details successfully.'),
                    [
                        'getHourlyCurrentBalancesByPoint' => $this->getHourlyCurrentBalancesByPoint($point->id),
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
                $point = Point::find($id);
                if (!$point) {
                    return $this->notFoundResponse('Point');
                }

                if (\request()->date) {
                    $startOfDay = Carbon::parse(\request()->date)->startOfDay()->toDateString();
                } else {
                    $startOfDay = Carbon::now()->toDateString();
                }

                return $this->okResponse(
                    __('Returned Point Details successfully.'),
                    [
                        'getMovementsCountByTypeAndDateAttribute' => $point->getMovementsCountByTypeAndDateAttribute($startOfDay),
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








    private function getHourlyCurrentBalancesByPoint(int $pointId, $date): array
    {
        $point = Point::findOrFail($pointId);
        $storageCapacity = $point->storage_capacity;

        if ($storageCapacity === 0) {
            return [];
        }

        $startOfDay = $date
            ? \Carbon\Carbon::parse($date)->startOfDay()
            : now()->startOfDay();

        $now = now();
        $currentHourLabel = $now->format('H:00');

        // Initial deposit & exchange before today
        $initial = Movement::where('point_id', $pointId)
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

        // Today's hourly movements (deposit & exchange)
        $hourlyRaw = Movement::where('point_id', $pointId)
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
                'percent'  => round(($runningBalance / $storageCapacity) * 100, 0),
            ];
        }

        return $hourlyData;
    }

    private function getHourlyCongestionLevelByPoint(int $pointId): array
    {
        $point = Point::findOrFail($pointId);

        $startOfDay = request()->hourly_date_congestion
            ? \Carbon\Carbon::parse(request()->hourly_date_congestion)->startOfDay()
            : now()->startOfDay();

        $now = now();
        $currentHourLabel = $now->format('H:00');


        $movements = Movement::where('point_id', $pointId)
            ->whereDate('created_at', $startOfDay)
            ->orderBy('created_at', 'asc')
            ->get();

        $lastMovement = Movement::where('point_id', $pointId)
            ->whereDate('created_at', '<', $startOfDay)
            ->orderBy('created_at', 'desc')
            ->first();

        // Group by hour
        $groupedByHour = $movements->groupBy(fn($item) => $item->created_at->format('H:00'));

        $hourlyData = [];
        $lastKnownCondition = $lastMovement ? $lastMovement->congestion_level : $point->congestion_level;

        for ($hour = 0; $hour < 24; $hour++) {
            $hourLabel = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';


            if ($hourLabel > $currentHourLabel && $startOfDay->toDateString() == $now->toDateString()) {
                break;
            }

            $currentHourMovements = $groupedByHour[$hourLabel] ?? collect();

            if ($currentHourMovements->isNotEmpty()) {
                $lastKnownCondition = $currentHourMovements->last()->congestion_level;
            }

            $hourlyData[] = [
                'hour'      => $hourLabel,
                'condition' => $lastKnownCondition ?? $point->congestion_level ?? 'unknown',
            ];
        }

        return $hourlyData;
    }

    public function getReviewersForPoint($pointId, $date)
    {
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();

        $topReviewersForPoint = Review::with(['user'])
            ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
            ->leftJoin('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.point_id', $pointId)
            ->whereNull('users.deleted_at')
            ->whereBetween('reviews.created_at', [$start, $end])
            ->select('reviews.user_id', 'reviews.description', 'reviews.other', 'reviews.id', 'reviews.created_at')
            ->selectRaw('COALESCE(AVG(review_standards.rate), 0) as average_rating_reviwers')
            ->groupBy('reviews.user_id', 'reviews.description', 'reviews.other', 'reviews.id', 'reviews.created_at')
            ->orderByDesc('reviews.created_at')
            ->limit(5)
            ->get();
        return ReviewResource::collection($topReviewersForPoint);
    }

    public function getReviewersForPointWithSort($pointId, array $filters = [])
    {
        $date = Carbon::parse($filters['date'] ?? now());
        $start = $date->copy()->startOfDay();
        $end = $date->copy()->endOfDay();
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'desc');
        if ($sortField == 'average_rating_reviwers') {
            $sortField = 'average_rating_reviwers';
        } else {
            $sortField = 'reviews.' . $sortField;
        }
        $topReviewersForPoint = Review::with(['user'])
            ->leftJoin('review_standards', 'reviews.id', '=', 'review_standards.review_id')
            ->leftJoin('users', 'users.id', '=', 'reviews.user_id')
            ->where('reviews.point_id', $pointId)
            ->whereNull('users.deleted_at')
            ->whereBetween('reviews.created_at', [$start, $end])
            ->select('reviews.user_id', 'reviews.description', 'reviews.other', 'reviews.id', 'reviews.created_at')
            ->selectRaw('COALESCE(AVG(review_standards.rate), 0) as average_rating_reviwers')
            ->groupBy('reviews.user_id', 'reviews.description', 'reviews.other', 'reviews.id', 'reviews.created_at')
            ->orderBy($sortField, $sortDirection)
            ->limit(5)
            ->get();
        return ReviewResource::collection($topReviewersForPoint);
    }

    public function getMovementsForPoint($id, array $filters = [])
    {
        try {

            $point = Point::with('reviews')->find($id);
            if (!$point) {
                return $this->notFoundResponse('Point');
            }
            $date = Carbon::parse($filters['date'] ?? now());
            // $date = Carbon::parse($request->input('date', now()) );
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();
            $sortField = $filters['sort_field'] ?? 'created_at';
            $sortDirection = $this->validateSortDirection($filters['sort_direction'] ?? 'desc');

            $movements = Movement::with('user')->where('point_id', $point->id)
                //->orderByDesc('created_at')
                ->whereBetween('movements.created_at', [$start, $end])
                ->limit(10)
                ->orderBy($sortField, $sortDirection)
                ->get();


            return $this->okResponse(
                __('Returned Point Details successfully.'),
                [

                    'movements' => MovementResource::collection($movements),
                ]
            );
        } catch (\Exception $exception) {
            dd($exception);
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
}
