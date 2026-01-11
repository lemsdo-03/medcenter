<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {//of not logged in
            return redirect()->route('login');//redirect to loging page
        }

        $user = Auth::user(); //get the logged in

        if (!in_array($user->role, $roles)) { //if its not inside the roles we created it will show errors
            abort(403, 'Unauthorized');
        }

        return $next($request);//if the role exist go on go the controller
    }
}
