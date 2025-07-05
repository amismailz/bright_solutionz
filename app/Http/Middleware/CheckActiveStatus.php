<?php

namespace App\Http\Middleware;

use App\Enums\StatusEnum;
use Closure;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class CheckActiveStatus
{
    use ResponseTrait;
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->status == StatusEnum::Inactive->value)
        {
            return $this->failedWithError(__('User is not activated.'), 401);
        }
        return $next($request);
    }
}
