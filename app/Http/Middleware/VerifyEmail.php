<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class VerifyEmail {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (Auth::user() && Auth::user()->email_verified_at) {
            return $next($request);
        } else {
          return  \App\Http\Controllers\API\ApiController::error("Your Email is not Verified",200);
        }
    }

}