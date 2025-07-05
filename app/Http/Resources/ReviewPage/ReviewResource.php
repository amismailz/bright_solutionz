<?php

namespace App\Http\Resources\ReviewPage;

use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'description' => $this->description,
            'images'      => $this->images,
            'videos'      => $this->videos,
            'other'       => $this->other,
            'range'       => new RangeResource($this->range),
            'point'       => new PointResource($this->point),
            'user'       => new UserResource($this->user),
            'reviewer_standards' => $this->reviewerStandards->map(function ($reviewStandard) {
                return [
                    'id'          => $reviewStandard->id,
                    'standard_id' => $reviewStandard->standard_id,
                    'standard'    => [
                        'id'   => $reviewStandard->standard->id,
                        'name' => $reviewStandard->standard->name,
                    ],
                    'rate'        => $reviewStandard->rate,
                    'description' => $reviewStandard->description,
                ];
            }),
        ];
    }
}
