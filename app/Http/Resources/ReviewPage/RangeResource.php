<?php

namespace App\Http\Resources\ReviewPage;

use Illuminate\Http\Resources\Json\JsonResource;

class RangeResource extends JsonResource
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
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => __($this->name),
            'points' => PointResource::collection($this->points),
        ];
    }
}
