<?php

namespace App\Http\Resources\SocietyPage;

use App\Http\Resources\ReviewPage\GroupFactorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SocietyResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'name'              => $this->name,
        ];
    }
}
