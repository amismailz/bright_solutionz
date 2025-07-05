<?php

namespace App\Http\Resources;

use App\Models\Association;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{

    public function toArray($request): array
    {

        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'slug'               => $this->slug,
            'lat'                => $this->lat,
            'long'               => $this->long,
            'storage_capacity'   => $this->storage_capacity,
            'current_balance'    => $this->current_balance,
            'congestion_level'   => $this->congestion_level,
            'association'         => new AssociationResource($this->association),
            'range'              => new RangeResource($this->whenLoaded('range')),
            'storage_usage_percentage' => ($this->storage_usage_percentage) ? round((float) $this->storage_usage_percentage, 2) : 0,
            'average_rating_reviwers' => $this->average_rating_reviwers,
            'average_rating_visitors' => $this->average_rating_visitors,
            'number_of_reviewers' => $this->number_of_reviewers,
            'number_of_visitors' => $this->number_of_visitors,
            'number_of_reviews' => $this->number_of_reviews,
            'total_deposit' => $this->total_deposit,
            'total_exchange' => $this->total_exchange,
            'movements_count_by_type_and_day' => $this->getMovementsCountByTypeAndDayAttribute(),
            'images' => $this->getMedia('images')->map(function ($media) {
                return [
                    'id'  => $media->id,
                    'url' => $media->getUrl(), // الصورة الأصلية
                    'thumb' => $media->getUrl('thumb'), // النسخة المصغرة إن وُجدت
                ];
            }),

        ];
    }
}
