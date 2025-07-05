<?php

namespace App\Http\Resources;

use App\Models\Association;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{

    public function toArray($request): array
    {

        return [
            // 'id' => $this->id,
            // 'name' => $this->name,
            // 'file_name' => $this->file_name,
            // 'mime_type' => $this->mime_type,
            // 'size' => $this->size,
            'url' => $this->getFullUrl(), // full URL to the image
            'thumbnail' => $this->getFullUrl('thumb'), // optional conversion
            // 'created_at' => $this->created_at,

        ];
    }
}
