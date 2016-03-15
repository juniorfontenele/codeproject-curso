<?php

namespace CodeProject\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
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
        return parent::report($e);
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
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'error' => false,
                'message' => [
                    'not_found' => 'Não encontrado'
                ]
            ], 404);
        }

        if ($e instanceof ValidatorException) {
            return response()->json([
                'error' => true,
                'valid' => false,
                'message' => $e->getMessageBag()
            ], 400);
        }

        if ($e instanceof CodeProjectException) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessageBag()
            ], $e->getCode());
        }

        return parent::render($request, $e);
    }
}
