<?php

namespace App\Services\API;

use App\Enums\RoleTypeEnum;
use App\Http\Requests\API\EditUserProfileRequest;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\UploadAttachmentsRequest;
use App\Http\Resources\PointResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Facades\Activity;

class AuthenticationService
{
    use ResponseTrait;
    public function register(array $data) {}

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            // $lat = floatval($request->input('lat', 0));
            // $long = floatval($request->input('long', 0));


            if (!Auth::attempt($credentials)) {
                return $this->failedWithError(__('Login failed. Please check your credentials.'), 401);
            }
            $user = Auth::user();
            if ($user->status != 'active') {
                return $this->failedWithError(__('User is not activated.'), 401);
            }
           $nearestPoint=$user->points()->first();

            // if ($user->disallow_location_track == '1') {
            //     $nearestPoint = $user->points->first();
            // } else {
            //     $nearestPoint = null;
            // }

            // if ($user->getRole() === RoleTypeEnum::Distributor->value && !$user->disallow_location_track == '1') {
            //     $lat = $request->input('lat') ? (float)$request->input('lat') : 0;
            //     $long = $request->input('long') ? (float)$request->input('long') : 0;
            //     $withinAllowedRange = collect($user->points)->contains(function ($point) use ($lat, $long, &$nearestPoint) {
            //         $distance = $this->calculateDistance($lat, $long, (float)$point->lat, (float)$point->long);
            //         //    less from 100 meter
            //         if ($distance < 0.1) {
            //             $nearestPoint = $point;
            //             return true;
            //         }

            //         return false;
            //     });
            //     if (!$withinAllowedRange) {
            //         return $this->failedWithError(__('Login failed due to location restriction.'), 403);
            //     }
            // }

            $user->update(['last_login' => now(), 'login_status' => 'active']);
            $user->load(['association', 'range', 'points']);
            $token = $user->createToken('auth_token')->plainTextToken;
            activity('auth')
                ->causedBy(auth()->user())
                ->withProperties([
                    'type' => 'login',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('User logged in');

            return $this->okResponse(__('Login successful'), ['user' => new UserResource($user), 'token' => $token, 'point' => ($nearestPoint) ? new PointResource($nearestPoint) : null]);
        } catch (\Exception $e) {
            return $this->failedWithError(__('Login failed'), 401);
        }
    }

    public function logout()
    {
        try {
            activity('auth')
                ->causedBy(auth()->user())
                ->withProperties([
                    'type' => 'logout',
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ])
                ->log('User logged out');
            Auth::user()->currentAccessToken()->delete();
            return $this->okResponse(__('Logout successful'));
        } catch (\Exception $e) {
            return $this->exceptionFailed(__('Logout failed'));
        }
    }
    public function editUserProfile(EditUserProfileRequest $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->unauthorized();
            }
            if ($user) {
                $user->update($request->all());
                //$user->update(request()->all());
                $user->load(['association', 'range', 'points']);
                return $this->okResponse(__('Update user profile successfully'), [
                    'user' => new UserResource($user)
                ]);
            } else {
                return $this->notFoundResponse('User');
            }
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
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

    public function uploadAttachments(UploadAttachmentsRequest $request)
    {
        try {
            $path = "/storage";
            $file = null;
            if (!empty(request()->has('file'))) {
                if (request()->name == 'avatar') {
                    $path = "/storage/users";
                    $file = $this->uploadAttachment(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'movement_image') {
                    $path = "movements/images";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'review_image') {
                    $path = "reviews/images";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'review_video') {
                    $path = "reviews/videos";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'evaluation_image') {
                    $path = "evaluations/images";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'evaluation_video') {
                    $path = "evaluations/videos";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'contest_file') {
                    $path = "contests/files";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'location_review_image') {
                    $path = "location-reviews/images";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                } elseif (request()->name == 'location_review_video') {
                    $path = "location-reviews/videos";
                    $file = $this->uploadAttachmentSecure(request()->file('file'), $path, request()->name);
                }
            }
            if (!$file) {
                return $this->failedWithError(__('Upload attachment failed'), 422); // 422 = Unprocessable Entity
            }
            return $this->okResponse(__('Upload attachments successfully'), ['key' => request()->name, 'data' => $file]);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            //dd($exception->getMessage());
            return $this->exceptionFailed($exception);
        }
    }
    public function uploadAttachmentSecure($file, $path, $name)
    {
        $fileName = time() . '_' . $name . '.' . $file->getClientOriginalExtension();
        $fullPath = trim($path, '/') . '/' . $fileName;
        Storage::disk('local')->putFileAs($path, $file, $fileName);
        return $fullPath;
    }
    public function uploadAttachment($file, $path, $name)
    {
        $fileName = time() . '_' . $name . '.' . $file->getClientOriginalExtension();
        $destinationPath = public_path($path);
        $file->move($destinationPath, $fileName);
        return $fileName;
    }
}
