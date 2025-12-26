<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using: function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            '/auth/session-login',
            '/auth/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if (! ($request->expectsJson() || $request->is('api/*'))) {
                return null;
            }

            $payload = [
                'status' => 'error',
            ];

            if ($e instanceof ValidationException) {
                $payload['message'] = 'Error de validación';
                $payload['errors'] = $e->errors();

                return response()->json($payload, 422);
            }

            if ($e instanceof QueryException) {
                Log::error('Database Error: ' . $e->getMessage(), [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                ]);

                $message = $e->getCode() == 23000
                    ? 'Violación de restricción única o clave foránea'
                    : 'Error en la base de datos';

                $payload['message'] = $message;

                return response()->json($payload, 500);
            }

            if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                $payload['message'] = 'Recurso no encontrado';

                return response()->json($payload, 404);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                $payload['message'] = 'Método HTTP no permitido';

                return response()->json($payload, 405);
            }

            if ($e instanceof AuthenticationException) {
                $payload['message'] = 'No autenticado';

                return response()->json($payload, 401);
            }

            $status = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : ($e->getCode() ?: 500);

            $payload['message'] = $e->getMessage() ?: 'Error general';
            $payload['line'] = $e->getLine();
            $payload['file'] = $e->getFile();

            return response()->json($payload, $status);
        });
    })->create();
