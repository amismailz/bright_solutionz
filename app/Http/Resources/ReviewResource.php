<?php

namespace App\Http\Resources;

use App\Models\Association;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{

    public function toArray($request): array
    {

        return [
            'id'                 => $this->id,
            'range'              => new RangeResource($this->whenLoaded('range')),
            'user'              => new UserResource($this->whenLoaded('user')),
            'point'              => new PointResource($this->whenLoaded('point')),
            // 'reviewerStandards'              => ($this->reviewerStandards),
            'reviewerStandards'              => $this->whenLoaded('reviewerStandards'),
            'average_rating' => $this->average_rating,
            'average_rating_reviwers' => (float) $this->average_rating_reviwers,
            'other'    => $this->other,
            'description'   => $this->description,
            'latitude'    => $this->latitude,
            'longitude'   => $this->longitude,
            'created_at'   => $this->created_at?->format('Y-m-d H:i:s'),
            // Images with full secure URLs
            'images' => is_array($this->images)
                ? array_map(fn($image) => route('download.secure.file', ['filename' => ltrim($image, '/')]), $this->images)
                : [],

            // Videos with full secure URLs
            'videos' => is_array($this->videos)
                ? array_map(fn($video) => route('download.secure.file', ['filename' => ltrim($video, '/')]), $this->videos)
                : [],

        ];
    }
}
