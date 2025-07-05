<?php

namespace App\Http\Resources\SocietyPage;

use Illuminate\Http\Resources\Json\JsonResource;

class SocietyGroupFactorResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'name'              => __($this->name),
            'type'              => $this->type,
            'options'               => $this->options,
        ];
    }
}
