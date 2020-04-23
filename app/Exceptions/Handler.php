<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            $code = 422;
            return response()->json([
                'error' => 'Los datos solicitados son invalidos.',
                'code' => $code,
                'message' => $exception->validator->getMessageBag(),
            ], $code);
        }

        if($exception instanceof NotFoundHttpException)
        {
            return response()->json([
                'error' => 'La ruta a la que intenta acceder no existe',
                'code' => $exception->getStatusCode()
            ],$exception->getStatusCode());
        } else {

            if($exception instanceof MethodNotAllowedHttpException)
            {
                return response()->json([
                    'error' => 'El metodo de acceso no esta permitido',
                    'code' => $exception->getStatusCode()
                ],$exception->getStatusCode());
            } else {
                return response()->json([
                    'error' => $exception->getMessage()
                ]);
            }
        }


        return parent::render($request, $exception);
    }
}
