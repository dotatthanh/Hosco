<?php

namespace App\Http\Middleware;

use Closure;

class UserHasRole
{
    /**
     * if user has role then next request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            // if (auth()->user()->getRoleNames()->count()) {
                return $next($request);
            // }
        }

        return redirect('/login');
        //return abort(404);
    }
}
