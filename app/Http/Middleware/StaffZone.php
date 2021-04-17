<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StaffZone
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
        if (Gate::allows('staff-action')) {
            return $next($request);
        } elseif ($request->ajax() || $request->wantsJson()) {
            return response('Unauthorized.', 401);
        } elseif (Auth::check()) {
            return response()->view('home');
        } else {
            return redirect()->guest('/')->with('notify', 'กรุณาเข้าสู่ระบบ');
        }
    }
}
