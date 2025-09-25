<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\ApiLoggerMiddleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Routing\Middleware\SubstituteBindings;
use App\Http\Responses\ResponseFormatter;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('api', [
            ApiLoggerMiddleware::class,
            // EnsureFrontendRequestsAreStateful::class,
            // 'throttle:api',
            SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (ValidationException $e, $request) {
            return ResponseFormatter::error(
                'Dữ liệu không hợp lệ',
                422,
                $e->errors()
            );
        });

        $exceptions->render(function (UnauthorizedHttpException $e, $request) {
            return ResponseFormatter::error(
                'Bạn không có quyền truy cập',
                401
            );
        });

        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return ResponseFormatter::error(
                    $e->getMessage() ?: 'Server Error',
                    method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500
                );
            }
        });
    })->create();
