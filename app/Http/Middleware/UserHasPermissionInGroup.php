<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserHasPermissionInGroup
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $roles = [];
        if(is_array($role)){
            $roles = $role;
        }else{
            array_push($roles, $role);
        }

        foreach($roles as $r){
            if($request->route('billgroup')->getUserRole(auth()->id())->slug === $r)
                return $next($request);
        }

        return response('No permissions',401);
    }
}
