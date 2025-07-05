<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'username'  => $this->username,
            'phone' => $this->phone,
            'email' => $this->email,
            'gender' => $this->gender,
            'login_status' => $this->login_status,
            'status' => $this->status,
            'role' => $this->getRole(),
            'avatar' => $this->avatar ? url('storage/users/' . $this->avatar) : null,
            'last_login' => $this->last_login,
            'association' => new AssociationResource($this->whenLoaded('association')),
            'range' => new RangeResource($this->whenLoaded('range')),
            'deleted_at' => $this->deleted_at,
            //'points' => PointResource::collection($this->whenLoaded('points')),

        ];
    }
}
