<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Helpers\ResponseHelper;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof ValidationException) {
            return ResponseHelper::apiException('参数不符!',$e->validator->errors(),422);
        }
        if ($e instanceof MethodNotAllowedHttpException) {
            return ResponseHelper::apiException('Method not allowed!',$e->getMessage(),405);
        }
        if ($e instanceof \ErrorException) {
            return ResponseHelper::apiException('系统内部错误!',$e->getMessage(),500);
        }
        if ($e instanceof \LogicException) {
            return ResponseHelper::apiException('系统内部错误!',$e->getMessage(),500);
        }
        return parent::render($request, $e);
    }
}
