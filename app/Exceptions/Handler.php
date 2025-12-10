<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponseTrait;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;


class Handler extends ExceptionHandler
{
    use ApiResponseTrait;

    public function register()
    {
        // Manejo de excepciones de validación
        $this->renderable(function (ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Error de validación', 422, $e->errors());
            }
        });

        // Manejo de errores de base de datos
        $this->renderable(function (QueryException $e, $request) {
            if ($request->expectsJson()) {
                \Log::error('Database Error: ' . $e->getMessage(), [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                ]);

                $message = 'Error en la base de datos';
                
                if ($e->getCode() == 23000) {
                    $message = 'Violación de restricción única o clave foránea';
                }

                return $this->errorResponse($message, 500);
            }
        });

        // Modelo no encontrado
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Recurso no encontrado', 404);
            }
        });

        // Ruta no encontrada
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Endpoint no encontrado', 404);
            }
        });

        // Método no permitido
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Método HTTP no permitido', 405);
            }
        });

        // No autenticado
        $this->renderable(function (AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return $this->errorResponse('No autenticado', 401);
            }
        });

        // Excepción general
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                return $this->errorResponse('Error general', 500, $e->getMessage(), $e->getLine());
            }
        });
    }
}