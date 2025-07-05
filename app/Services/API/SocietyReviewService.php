<?php

namespace App\Services\API;;

use App\Enums\CongestionLevelEnum;
use App\Events\ReviewEvent;
use App\Http\Requests\API\MovementRequest;
use App\Http\Requests\API\ReviewRequest;
use App\Http\Requests\API\SocietyReviewRequest;
use App\Http\Resources\MovementResource;
use App\Http\Resources\ReviewPage\GroupResource;
use App\Http\Resources\ReviewPage\RangeResource;
use App\Http\Resources\ReviewPage\ReviewResource;
use App\Http\Resources\ReviewPage\StanderResource;
use App\Http\Resources\SocietyPage\SocietyGroupResource;
use App\Http\Resources\SocietyPage\SocietyResource;
use App\Models\Group;
use App\Models\Movement;
use App\Models\Point;
use App\Models\Range;
use App\Models\Review;
use App\Models\ReviewFactor;
use App\Models\ReviewStandard;
use App\Models\Society;
use App\Models\SocietyGroup;
use App\Models\SocietyReview;
use App\Models\SocietyReviewFactor;
use App\Models\Standard;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SocietyReviewService
{

    use ResponseTrait;


    public function store(SocietyReviewRequest $request)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->isSupervisor()) {
                return $this->unauthorized();
            }

            $data = $request->validated();

            DB::beginTransaction();

            $review = SocietyReview::create([
                'user_id'      => $user->id,
                'society_name' => $data['society_name'],
                'latitude'     => $data['latitude'],
                'longitude'    => $data['longitude'],
                'images'       => $data['images'] ?? [],
                'videos'       => $data['videos'] ?? [],
            ]);

            // Save related factors
            foreach ($data['factors'] as $factor) {
                SocietyReviewFactor::create([
                    'society_review_id'        => $review->id,
                    'society_group_factor_id'  => $factor['society_group_factor_id'],
                    'value'                    => $factor['value'],
                    'description'              => $factor['description'] ?? null,
                ]);
            }

            DB::commit();

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
            'societies' => SocietyResource::collection(Society::get()),
            'groups'   => SocietyGroupResource::collection(SocietyGroup::with('factors')->get()),
        ];
    }
}
