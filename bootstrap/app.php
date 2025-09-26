<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\InstallationCheck;
use App\Http\Middleware\DemoUserRestriction;
use Spatie\Permission\Middleware\RoleMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'demo.restrict' => DemoUserRestriction::class,
        ]);

        // InstallationCheck middleware globally attach
        $middleware->append(InstallationCheck::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
