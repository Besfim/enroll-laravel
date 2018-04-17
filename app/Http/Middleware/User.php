<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
use Illuminate\Support\Facades\Session;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Session::has('user'))
        {
            Session::put('log2url',$request->url());
            if(!$request->cookie('userPhone'))
                return redirect('userLog');
            else
            {
                $user = Users::where(['phone' => $request->cookie('userPhone'),'password' => $request->cookie('userPassword')])->first();
                if($user)
                {
                    Session::put('user',$user->id);
                    return $next($request);
                }
                else
                    return redirect('userLog');
            }
        }
        else
            return $next($request);
    }
}
