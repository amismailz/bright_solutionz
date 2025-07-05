<?php

namespace App\Http\Resources\SocietyPage;

use App\Http\Resources\ReviewPage\GroupFactorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SocietyGroupResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'slug'               => $this->slug,
            'name'              => __($this->name),
            'factors' => SocietyGroupFactorResource::collection($this->factors),
        ];
    }
}
