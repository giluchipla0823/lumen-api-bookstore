<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $this->authenticate($request);
        }
        catch (UnauthorizedHttpException $exception){
            return $this->respondException($exception);
        }

        return $next($request);
    }

    public function respondException(UnauthorizedHttpException $exception){
        $code = $exception->getStatusCode();
        $message = 'Token not provided';

        if($exception->getPrevious() instanceof TokenInvalidException){
            $message = 'Token invalid';
        }

        if($exception->getPrevious() instanceof TokenExpiredException){
            $message = 'Token expired';
        }

        return $this->errorResponse($message, $code);
    }


}
