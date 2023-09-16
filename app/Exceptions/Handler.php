<?php

namespace App\Exceptions;

use App\Enums\Status;
use App\Http\JsonApiResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonApiResponse
    {
        if ($e instanceof NotFoundHttpException) {
            return new JsonApiResponse(['message' => 'Not found.'], Status::NOT_FOUND);
        }

        return parent::render($request, $e);
    }
}
