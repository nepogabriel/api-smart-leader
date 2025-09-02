<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function login(array $credentials): array
    {
        try {
            if (!$token = auth()->attempt($credentials)) {
                return [
                    'return' => [
                        'message' => 'Login não autorizado.',
                    ],
                    'code' => Response::HTTP_UNAUTHORIZED,
                ];
            }

            return [
                'return' => [
                    'data' => $this->respondWithToken($token),
                    'message' => 'Login realizado com sucesso.',
                ],
                'code' => Response::HTTP_OK,
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao efetuar login: ', [
                'message' => $exception->getMessage(),
                'code-http' => $exception->getCode()
            ]);

            return [
                'return' => [
                    'message' => 'Erro ao efetuar login.',
                ],
                'code' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ];
        }
    }

    public function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}