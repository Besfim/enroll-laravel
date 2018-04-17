<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Manager
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
        if(!Session::has('manager'))
            return redirect('adminLog');
        $manager = \App\Manager::find(Session::get('manager'));
        if($manager->type == 3 && $manager->aid == 0)
        {
            if(!in_array($request->route()->uri(),['createAssociation','manager','varyManager','admin']))
                return redirect('admin')->with('msg','未有社团身份，请先创建社团');
        }
        return $next($request);
    }
}
