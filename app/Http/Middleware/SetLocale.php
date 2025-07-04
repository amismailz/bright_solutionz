<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle($request, Closure $next)
    // {
    //     $locale = session('locale', config('app.locale'));
    //     App::setLocale($locale);

    //     return $next($request);
    // }
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', config('app.locale'));
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        setlocale(LC_ALL, $locale); // ✅ Correct usage

        return $next($request);
    }
}
