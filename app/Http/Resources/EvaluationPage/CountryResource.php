<?php

namespace App\Http\Resources\EvaluationPage;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
