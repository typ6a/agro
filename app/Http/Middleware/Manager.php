<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Manager
{
    public function handle($request, Closure $next)
    {
        if(!Auth::check()) {
            return redirect('users/login');
        } else {
            $user = Auth::user();
            if($user->hasRole('manager'))
            {
                return $next($request);
            } else {
                return redirect('/');
            }
        }
    }
}






// namespace App\Http\Middleware;

// use Closure;

// class Manager
// {
//     *
//      * Handle an incoming request.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \Closure  $next
//      * @return mixed
     
//     public function handle($request, Closure $next)
//     {
//         return $next($request);
//     }
// } 