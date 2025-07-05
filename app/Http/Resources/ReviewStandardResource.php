<?php

namespace App\Http\Resources;

use App\Models\Association;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewStandardResource extends JsonResource
{

    public function toArray($request): array
    {

        return [
            'id'                 => $this->id,
            'review'             => new ReviewResource($this->whenLoaded('review')),
            'standard'           => new StandardResource($this->whenLoaded('standard')),
            'rate'    => $this->rate,
        ];
    }
}
