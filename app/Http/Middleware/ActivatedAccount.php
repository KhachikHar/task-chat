<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class ActivatedAccount
{
    public function handle($request, Closure $next){
        if(Auth::user()->activate == 0){
            return Redirect::route('not.activated.account');
        }
        return $next($request);
    }
}
