<?php

namespace App\Http\Resources\ReviewPage;

use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'name'              => __($this->name),
        ];
    }
}
