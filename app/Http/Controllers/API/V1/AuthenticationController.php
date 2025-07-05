<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\API\ChangePasswordRequest;
use App\Http\Requests\API\EditUserProfileRequest;
use App\Http\Requests\API\ForgetPasswordRequest;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Requests\API\UploadAttachmentsRequest;
use App\Http\Requests\API\VerifyOTPRequest;
use App\Services\API\AuthenticationService;
use App\Traits\ResponseTrait;

class AuthenticationController extends Controller
{
    use ResponseTrait;
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request) {}

    public function login(LoginRequest $request)
    {
      return  $this->authService->login($request);
    }
    public function logout()
    {
      return  $this->authService->logout();
    }
    public function editUserProfile(EditUserProfileRequest $request)
    {
      return  $this->authService->editUserProfile($request);
    }
    public function uploadAttachments(UploadAttachmentsRequest $request)
    {
        return  $this->authService->uploadAttachments($request);
    }
}
