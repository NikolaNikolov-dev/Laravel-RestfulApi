<?php

namespace App\Exceptions;

use Throwable;
use App\Traits\ApiResponser;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PDOException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
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
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function ( $request,Throwable $e ) {
            if ($e instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($e,$request);
            }

            if ($e instanceof ModelNotFoundException) {
                $modelName = strtolower(class_basename($e->getModel()));
                return $this->errorResponse("Does not exist any {$modelName} with the specified identificator",404);
            }

            if ($e instanceof AuthenticationException) {
                return $this->unauthenticated($request,$e);
            }

            if ($e instanceof AuthorizationException) {
                return $this->errorResponse($e->getMessage(),403);
            }

            if ($e instanceof MethodNotAllowedException) {
                return $this->errorResponse('The specified method for the request is invalid.',405);
            }

            if ($e instanceof NotFoundHttpException) {
                return $this->errorResponse('The specified URL cannot be found',404);
            }

            if ($e instanceof HttpException) {
                return $this->errorResponse($e->getMessage(),$e->getStatusCode());
            }

            if ($e instanceof PDOException) {

                $errorCode = $e->errorInfo[1];

                if($errorCode == 1451){

                    return $this->errorResponse('Cannot remove resource permanently. It is related with any other resource',409);
                }
            }

            if (config('app.debug')) { // if app\config is in debug mode
                return $this->errorResponse($request,$e);
            }

            return $this->errorResponse('Unexpected Exception. Try again later', 500);
        });
    }

    protected function convertValidationExceptionToResponse(ValidationException $e,$request){
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors,422);
    }
}
