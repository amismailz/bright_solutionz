<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       /**
     * requests hasHeader is used to check the Accept-Language header from the REST API's
     */
    if ($request->hasHeader("Accept-Language")) {
      
        /**
         * If Accept-Language header found then set it to the default locale
         */
        if (!in_array($request->header("Accept-Language"), config('app.locales'))) {
            App::setLocale(config('app.locale'));
        } else {
            App::setLocale($request->header("Accept-Language"));
        }
    }else{
        App::setLocale('en');
    }
    return $next($request);
    }
}
