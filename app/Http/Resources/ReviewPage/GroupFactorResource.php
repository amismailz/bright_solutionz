<?php

namespace App\Http\Resources\ReviewPage;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupFactorResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'slug'               => $this->slug,
            'name'              => __($this->name),
        ];
    }
}
