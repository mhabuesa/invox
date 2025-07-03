<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\InstallationInfo;
use Symfony\Component\HttpFoundation\Response;

class InstallationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $installed = file_exists(storage_path('installed'));

        if (!$installed && !$request->is('install*') && !$request->expectsJson()) {
            return redirect('/install');
        }

        return $next($request);
    }
}
