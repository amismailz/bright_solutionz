<?php

namespace App\Http\Resources;

use App\Models\Association;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaReviewResource extends JsonResource
{

    public function toArray($request): array
    {

        return [

            'images' => is_array($this->images)
                ? array_map(fn($image) => route('download.secure.file', ['filename' => ltrim($image, '/')]), $this->images)
                : [],
        ];
    }
}
