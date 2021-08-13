<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProtectClientRoutesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::guest()){
            return redirect('home');
        }

        if (auth()->user()->user_type == 0) {
            return $next($request);
        }

        return redirect()->back();
    }
    
}
