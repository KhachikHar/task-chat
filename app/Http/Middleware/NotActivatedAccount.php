<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class NotActivatedAccount
{
    public function handle($request, Closure $next){
        if(Auth::user()->activate == 1){
            return Redirect::route('home');
        }
        return $next($request);
    }
}
