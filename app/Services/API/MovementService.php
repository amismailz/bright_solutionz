<?php

namespace App\Services\API;;

use App\Enums\CongestionLevelEnum;
use App\Enums\MovementTypeEnum;
use App\Enums\RoleTypeEnum;
use App\Events\MovementEvent;
use App\Events\ReviewEvent;
use App\Http\Requests\API\MovementRequest;
use App\Http\Resources\MovementResource;
use App\Http\Resources\PointResource;
use App\Http\Resources\ReviewPage\ReviewResource;
use App\Models\Movement;
use App\Models\Point;
use App\Models\Range;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class MovementService
{

    use ResponseTrait;

    public function store(MovementRequest $request)
    {
        try {
            $user = Auth::user();
            $data = $request->validated();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user->getRole() != RoleTypeEnum::Distributor->value) {
                return $this->badRequestResponse(__('You do not have permission to store movement this page.'));
            }
            $point = Point::find($data['point_id']);
            if (!$point) {
                return $this->notFoundResponse('Point');
            }
            $withinAllowedRange = false;
            $lat = $request->input('lat') ? (float)$request->input('lat') : 0;
            $long = $request->input('long') ? (float)$request->input('long') : 0;
            Log::info('Received location in movement store request.', [
                'lat' => $lat,
                'long' => $long,
                'user_id' => optional($user)->id,
                'point_id' => $data['point_id'] ?? null,
            ]);
            $distance = $this->calculateDistance($lat, $long, (float)$point->lat, (float)$point->long);
            //    less from 150 meter
            if ($distance < 0.2 || $user->disallow_location_track == '1') {
                $withinAllowedRange = true;
            } else {
                $withinAllowedRange = false;
            }
            if (!$withinAllowedRange) {
                return $this->failedWithError(__('Submit failed due to location restriction.'), 403);
            }

            $data['user_id'] = $user->id;
            $data['association_id'] = $user->association_id;
            $data['image'] = $request->movement_image;
            $data['number'] = rand(100000, 999999);


            if ($data['quantity'] > $point->storage_capacity) {
                return $this->failedWithError(
                    __('Quantity exceeds the allowed storage capacity (:capacity)', [
                        'capacity' => $point->storage_capacity,
                    ]),
                    422
                );
            }
            if ($data['type'] === MovementTypeEnum::Exchange->value) {
                if ($point->current_balance < $data['quantity']) {
                    return $this->failedWithError(__('The quantity exceeds the current balance.'), 422);
                }
                // $totalQuantity = $point->current_balance - $data['quantity'];
            } else {
                $totalQuantity = $point->current_balance + $data['quantity'];
                if ($totalQuantity > $point->storage_capacity) {
                    return $this->failedWithError(
                        __('You cannot deposit more than your storage capacity (:capacity)', ['capacity' => $point->storage_capacity]),
                        422
                    );
                }
            }

            $movement = Movement::create($data);
            Movement::updatePointBalance($point->id);
            $point->update([
                //'current_balance' => $totalQuantity,
                'congestion_level' => $request->congestion_level
            ]);

            try {
                if (!is_null($data['point_id'])) {

                    $point = Point::find($data['point_id']);
                    $range = Range::find($point->range_id);

                    $statistics = StatisticsService::getStatistics($range, $point);
                    event(new MovementEvent(new MovementResource($movement), $statistics));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send enven : ' . $e->getMessage());
            }

            $returnMessage = ($data['type'] == MovementTypeEnum::Deposit->value) ? __('Deposit completed successfully.') : __('ُExchange completed successfully.');
            return $this->createdResponseWithMessage($returnMessage, [
                'movement' => new MovementResource($movement),
                'point' => new PointResource($point)
            ]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            //dd($exception);
            return $this->exceptionFailed($exception);
        }
    }
    public function getCongestionLeveOptions()
    {

        return [
            CongestionLevelEnum::High->value => __('High'),
            CongestionLevelEnum::Medium->value => __('Medium'),
            CongestionLevelEnum::Low->value => __('Low'),
        ];
    }
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // Radius of Earth in kilometers
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;

        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
    public function getMovement($id)
    {
        try {
    
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user->getRole()  === RoleTypeEnum::AdminSupervisor->value) {
                $movement = Movement::with('user','point','point.range')->find($id);
                if (!$movement) {
                    return $this->notFoundResponse('Movement');
                }
                return $this->okResponse(
                    __('Returned Movement Details successfully.'),
                    
                         new MovementResource($movement),
                    
                );
            } else {
                return $this->badRequestResponse(__('You do not have permission to view this page.'));
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            // dd($exception);
            return $this->exceptionFailed($exception);
        }
    }
}
