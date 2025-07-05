<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssociationResource extends JsonResource
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
            'name' => $this->name,
            'contact_person' => $this->contact_person,
            'mobile' => $this->mobile,
            'email' => $this->email,
        ];
    }
}
