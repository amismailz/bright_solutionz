<?php

namespace App\Services\API;;

use App\Events\EvaluationEvent;
use App\Http\Requests\API\EvaluationRequest;
use App\Http\Resources\EvaluationPage\CountryResource;
use App\Http\Resources\ReviewPage\RangeResource;
use App\Http\Resources\ReviewPage\StanderResource;
use App\Models\Country;
use App\Models\Evaluation;
use App\Models\EvaluationStandard;
use App\Models\Point;
use App\Models\Range;
use App\Models\Review;
use App\Models\ReviewStandard;
use App\Models\Standard;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluationService
{

    use ResponseTrait;

    public function store(EvaluationRequest $request)
    {
        try {

            $data = $request->validated();
            DB::beginTransaction();
            
            $point=Point::where('slug',$data['point_slug'])->first();
            $evaluation = Evaluation::create([
                'note' => $data['note'] ?? null,
                'images' => $data['images'] ?? [],
                'videos' => $data['videos'] ?? [],
                'age' => $data['age'] ?? null,
                'country_id' => $data['country_id'] ?? null,
                'point_id' => $point->id ?? null,
            ]);

            foreach ($data['standers'] as $standard) {
                EvaluationStandard::create([
                    'evaluation_id' => $evaluation->id,
                    'standard_id' => $standard['standard_id'],
                    'rate' => $standard['rate'],
                ]);
            }

            DB::commit();


            if (!is_null($data['point_slug']))
            {
                $point = Point::where('slug', $data['point_slug'])->first();
                $range = $point->range;
                $statistics = StatisticsService::getStatistics($range,$point);
                event(new EvaluationEvent($evaluation,$statistics));
            }

            return $this->createdResponseWithMessage(__('The evaluation has been submitted successfully.'), []);
        } catch (\Exception $exception) {
            DB::rollBack();
              dd($exception->getMessage());
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }


    public function getOptions()
    {
        return [
            'cite' => CountryResource::collection(Country::get()),
            'standers' => StanderResource::collection(Standard::get()),
        ];
    }
}
