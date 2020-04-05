<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use App\Traits\ResponseTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    use ApiResponse, ResponseTransformer;

    /**
     * Get the token array structure.
     *
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->successResponse([
            'user' => Auth::user(),
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ], 'Las credenciales de acceso son correctas.');
    }
}