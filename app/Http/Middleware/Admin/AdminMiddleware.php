<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Auth;
use App\User;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $slug = User::getSlug(Auth::id())[0];
        if ($slug->slug == "admin") {
            return $next($request);
        }
        return redirect('/home');
    }
}
