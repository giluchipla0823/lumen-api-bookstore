<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Auth\AuthRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends ApiController
{
    /**
     * Get a JWT via given credentials.
     *
     * @param AuthRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = Auth::attempt($credentials)) {
            throw new AuthenticationException('Credenciales de acceso incorrectas');
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        return $this->successResponse(Auth::user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout(){
        Auth::logout();

        return $this->showMessage('Se ha cerrado la sesión del usuario correctamente.');
    }
}