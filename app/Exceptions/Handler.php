<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($e instanceof ValidationException) {
                return response()->json($e->errors(), 422);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        // Handle ValidationException
        if ($exception instanceof ValidationException) {
            return response()->json($exception->errors(), 422);
        }
    
        // Handle AuthorizationException
        if ($exception instanceof AuthorizationException) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Default exception handling
        return parent::render($request, $exception);
    }
}
