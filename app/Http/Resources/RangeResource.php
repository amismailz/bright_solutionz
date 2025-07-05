<?php

namespace App\Http\Resources;

use App\Models\Society;
use Illuminate\Http\Resources\Json\JsonResource;

class RangeResource extends JsonResource
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
            'slug' => $this->slug,
            'name' => $this->name,
            
            'city'         => new CityResource($this->city),
            'points_count' => $this->points_count,
            'association_count' => $this->association_count,
            'storage_capacity_sum' => $this->storage_capacity_sum,
            'total_storage_usage_percentage' => $this->total_storage_usage_percentage,
            'total_deposit_of_Points' => $this->total_deposit_of_Points,
            'total_exchange_of_Points' => $this->total_exchange_of_Points,
            'total_remaining_balance_percentage' => $this->total_remaining_balance_percentage,
            'movements_count_by_type_and_day' => $this->getMovementsCountByTypeAndDayAttribute(),
            'total_current_balance' => $this->total_current_balance
        ];
    }
}
