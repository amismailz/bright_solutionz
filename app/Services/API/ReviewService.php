<?php

namespace App\Services\API;;

use App\Enums\CongestionLevelEnum;
use App\Events\ReviewEvent;
use App\Http\Requests\API\MovementRequest;
use App\Http\Requests\API\ReviewRequest;
use App\Http\Resources\MovementResource;
use App\Http\Resources\ReviewPage\GroupResource;
use App\Http\Resources\ReviewPage\RangeResource;
use App\Http\Resources\ReviewPage\ReviewResource;
use App\Http\Resources\ReviewPage\StanderResource;
use App\Models\Group;
use App\Models\Movement;
use App\Models\Point;
use App\Models\Range;
use App\Models\Review;
use App\Models\ReviewFactor;
use App\Models\ReviewStandard;
use App\Models\Standard;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReviewService
{

    use ResponseTrait;


    public function store(ReviewRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }

            if (!$user->isSupervisor()) {
                return $this->unauthorized();
            }

            $data = $request->validated();

            DB::beginTransaction();

            $review = Review::create([
                'user_id' => $user->id,
                'range_id' => $data['range_id'] ?? null,
                'point_id' => $data['point_id'] ?? null,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'images' => $data['images'] ?? [],
                'videos' => $data['videos'] ?? [],
                'other' => $data['other'] ?? null,
            ]);

            // Attach standards with extra data
            foreach ($data['standers'] as $standard) {
                ReviewStandard::create([
                    'review_id' => $review->id,
                    'standard_id' => $standard['standard_id'],
                    'rate' => $standard['rate'],
                    'description' => $standard['description'] ?? null,
                ]);
            }


            if (isset($data['factors']))
            {
                foreach ($data['factors'] as $factor) {
                    ReviewFactor::create([
                        'review_id' => $review->id,
                        'group_factor_id'    => $factor['group_factor_id'],
                        'value'              => $factor['value'],
                        'description'        => $factor['description'] ?? null,
                    ]);
                }
            }



            DB::commit();
            if (isset($data['point_id']))
            {
                $point = Point::find($data['point_id']);
                $range = Range::find($point->range_id);

                $statistics = StatisticsService::getStatistics($range,$point);

                event(new ReviewEvent(new ReviewResource($review),$statistics));

            }
            return $this->createdResponseWithMessage(__('The evaluation has been submitted successfully.'), []);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }


    public function getOptions()
    {

        $user = Auth::user();
        if (!$user) {
            return $this->unauthorized();
        }

        if (!$user->isSupervisor()) {
            return $this->unauthorized();
        }

        return [
            'ranges' => RangeResource::collection(Range::with('points')->get()),
            'standers' => StanderResource::collection(Standard::get()),
            'groups'   => GroupResource::collection(Group::with('factors')->get()),
        ];
    }
}
