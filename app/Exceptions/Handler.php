<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->validationOutput($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Format validation output
     *
     * @param $e
     * @return Response
     */
    public function validationOutput($e)
    {
        $validator = $e->validator;

        $messages = $validator->getMessageBag()->getMessages();

        $key = key($messages);

        $res = [
            'error' => [
                [
                    'type' => "validation",
                    'field' => $key,
                    'message' => $messages[$key][0]
                ]
            ]
        ];

        return ( new Response($res , 422) )->header('Content-Type', 'application/json');
    }
}
