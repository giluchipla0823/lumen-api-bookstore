<?php

namespace App\Exceptions;

use App\Helpers\ValidationHelper;
use App\Libraries\Api;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;

    public $dataException;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class
    ];



    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $e
     * @return void
     * @throws Exception
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return $this|RedirectResponse|JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (config('app.debug')) {
            return parent::render($request, $exception);
        }

        $this->resolveDataException($exception);

        if($exception instanceof ValidationException){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));

            return $this->errorResponse(
                "No existe ninguna instancia de {$modelName} con el id especificado",
                Response::HTTP_NOT_FOUND
            );
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse(
                'Unauthorized',
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse(
                'El método especificado en la petición no es válido',
                Response::HTTP_METHOD_NOT_ALLOWED
            );
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse(
                'No se encontró la URL especificada',
                Response::HTTP_NOT_FOUND
            );
        }

        if($exception instanceof QueryException){
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_INTERNAL_SERVER_ERROR,
                Api::CODE_ERROR_DB
            );
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse(
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        }

        if($exception instanceof Exception){
            return $this->errorResponse(
                $exception->getMessage(),
                $exception->getCode()
            );
        }

        return $this->errorResponse(
            'Falla inesperada. Intente luego',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Resolver los valores de excepción a mostrar en la
     * respuesta de error en formato JSON
     *
     * @param $exception
     */
    protected function resolveDataException($exception){
        $this->dataException = [
            'class' => get_class($exception),
            'file' => $exception->getFile(),
            'line' => $exception->getLine()
        ];
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|RedirectResponse
     */
    protected function unauthenticated(Request $request, AuthenticationException $exception)
    {
        return $this->errorResponse(
            $exception->getMessage(),
            Response::HTTP_UNAUTHORIZED
        );
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $exc
     * @return $this|JsonResponse
     */
    protected function convertValidationExceptionToResponse(ValidationException $exc)
    {
        $errors = ValidationHelper::formatErrors($exc->validator->errors()->getMessages());

        return $this->errorResponse(
            'Data validation error',
            Response::HTTP_UNPROCESSABLE_ENTITY,
            Api::CODE_ERROR,
            [Api::IDX_STR_JSON_ERRORS => $errors]
        );
    }

}
