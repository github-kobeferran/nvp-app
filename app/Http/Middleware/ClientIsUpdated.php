<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientIsUpdated
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

        if(!auth()->user()->isAdmin()){  

            if(is_null(auth()->user()->client->image) || is_null(auth()->user()->name) || is_null(auth()->user()->client->sex) || is_null(auth()->user()->client->dob) || is_null(auth()->user()->client->contact) || is_null(auth()->user()->client->image) || is_null(auth()->user()->client->address))
                return redirect()->back();                
            else    
                return $next($request);

        }else{
            return $next($request);
        }

        return redirect()->back();
    }
}
