<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

        } catch (\Exception $exception) {
            Log::error('Erro no JWT: ', [
                'message' => $exception->getMessage(),
                'code-http' => $exception->getCode()
            ]);

            if ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $data = [
                    'message' => 'Token de autorização inválido!',
                ];

                return ApiResponse::response($data, Response::HTTP_UNAUTHORIZED);
            } elseif ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $data = [
                    'message' => 'Token de autorização expirado!',
                ];

                return ApiResponse::response($data, Response::HTTP_UNAUTHORIZED);
            } else {
                $data = [
                    'message' => 'Token de autorização não encontrado!',
                ];

                return ApiResponse::response($data, Response::HTTP_UNAUTHORIZED);
            }
        }

        return $next($request);
    }
}
