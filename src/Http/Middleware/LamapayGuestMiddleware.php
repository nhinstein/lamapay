<?php

namespace Nhinstein\Lamapay\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LamapayGuestMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {

        if(auth()->guard(config('lamapay.auth.guard', 'web'))->check()) {
            return redirect()->route('administrator.index');
        }

        Auth::shouldUse(config('lamapay.auth.guard', 'web'));
        return $next($request);
    }
}
