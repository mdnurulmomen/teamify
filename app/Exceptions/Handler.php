<?php

// /////////////////////////////////////////////////////////////////////////////
// PLEASE DO NOT RENAME OR REMOVE ANY OF THE CODE BELOW. 
// YOU CAN ADD YOUR CODE TO THIS FILE TO EXTEND THE FEATURES TO USE THEM IN YOUR WORK.
// /////////////////////////////////////////////////////////////////////////////

namespace App\Exceptions;

use Throwable;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Exception $e, $request) {

            if ($request->wantsJson()) {

                if ($e instanceof ModelNotFoundException) {

                    return response()->json([
                        'message' => "Model not found",
                    ], 404);

                } else if ($e instanceof NotFoundHttpException) {

                    return response()->json([
                        'message' => "Record not found",
                    ], 404);

                } else if ($e instanceof ValidationException) {

                    return response()->json([
                        'message' => $e->getMessage()
                    ], 422);

                } else if ($e instanceof QueryException) {

                    return response()->json([
                        'message' => $e->getMessage()
                    ], 500);

                } else {

                    return response()->json([
                        'message' => 'Bad request'
                    ], 400);

                }

            }

        });
    }
}
