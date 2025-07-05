<?php

namespace App\Services\API;;

use App\Enums\CongestionLevelEnum;
use App\Http\Requests\API\ContestRequest;
use App\Http\Resources\ContestPage\TagResource;
use App\Models\ContestParticipant;
use App\Models\ContestParticipantTag;
use App\Models\Review;
use App\Models\ReviewStandard;
use App\Models\Tag;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

class ContestService
{

    use ResponseTrait;

    public function store(ContestRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }

            $agent = new Agent();

            $data = $request->validated();


            $filePath = $data['file'];
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));


            $type = in_array($extension, ['mp4', 'mov', 'avi', 'webm']) ? 'video' : 'image';


            DB::beginTransaction();

            $participant = ContestParticipant::create([
                'user_id'     => Auth::id(),
                'type'        => $type,
                'file'        => $data['file'],
                'description' => $data['description'] ?? null,
                'user_agent'  => $request->userAgent(),
                'is_mobile'   => $agent->isMobile(),
                'is_tablet'   => $agent->isTablet(),
                'is_desktop'  => $agent->isDesktop(),
                'platform'    => $agent->platform(),
                'browser'     => $agent->browser(),
                'device'      => $agent->device(),
                'ip'          => $request->ip(),
                'role'        => Auth::user()?->getRole(),
            ]);

            foreach ($data['tags'] as $tag) {
                ContestParticipantTag::create([
                    'contest_participant_id' => $participant->id,
                    'tag_id' => $tag,
                ]);
            }

            DB::commit();


            return $this->createdResponseWithMessage(__('Created successfully.'), []);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }


    public function getOptions()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->unauthorized();
        }

        return [
            'tags' => TagResource::collection(Tag::get())
        ];
    }
}
