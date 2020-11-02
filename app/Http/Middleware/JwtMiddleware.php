<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
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
     * @return mixed|void
     * @throws JWTException
     */
    public function handle($request, Closure $next)
    {
        /* @var Request $request */
        if($token = $request->query->get('token')){
            $request->headers->add(['Authorization' => "Bearer {$token}"]);
        }

        try {
            $this->authenticate($request);
        }
        catch (UnauthorizedHttpException $exception){
            return $this->respondException($exception);
        }

        return $next($request);
    }

    /**
     * Mostrar error de JWT
     *
     * @param UnauthorizedHttpException $exception
     * @throws JWTException
     */
    public function respondException(UnauthorizedHttpException $exception){
        if(!$exception->getPrevious()){
            throw $exception;
        }

        $code = $exception->getStatusCode();
        $message = 'Undefined error message for token exception';

        if($exception->getPrevious() instanceof TokenInvalidException){
            $message = 'Token invalid';
        }

        if($exception->getPrevious() instanceof TokenExpiredException){
            $message = 'Token expired';
        }

        throw new JWTException($message, $code);
    }
}
