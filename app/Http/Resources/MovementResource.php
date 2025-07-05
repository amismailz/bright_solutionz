<?php

namespace App\Http\Resources;

use App\Http\Resources\ReviewPage\RangeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementResource extends JsonResource
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
            'number' => $this->number,
            'lat' => $this->lat,
            'long' => $this->long,
            'type' => $this->type,
            'congestion_level' => $this->congestion_level,
            // 'image' => $this->image ? url('storage/private/' . $this->image) : null,
            'quantity' => $this->quantity,
            'user' => new UserResource($this->whenLoaded('user')),
            'point' => new PointResource($this->whenLoaded('point')),
            'range' => new RangeResource($this->whenLoaded('range')),
            'association' => new AssociationResource($this->whenLoaded('association')),
            'image' => is_array($this->image)
                ? array_map(fn($image) => route('download.secure.file', ['filename' => ltrim($image, '/')]), $this->image)
                : [],


            'created_at' => $this->created_at,
        ];
    }
}
