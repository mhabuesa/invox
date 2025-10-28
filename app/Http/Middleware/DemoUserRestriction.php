<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoUserRestriction
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->email === 'demo@codehntr.com') {
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {

                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Demo user can not perform this action.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Demo user can not perform this action.');
            }
        }

        return $next($request);
    }
}
