<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function __construct(
        private CompanyService $companyService
    ) {}

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

    public function me()
    {
        try {
            if (!auth()->user()) {
                return [
                    'return' => [
                        'message' => 'Ops! Ocorreu algo, tente novamente ou mais tarde.',
                    ],
                    'code' => Response::HTTP_UNAUTHORIZED,
                ];
            }

            $data = auth()->user();

            $company = $this->companyService->findCompanyById(auth()->user()->company_id);

            $data['company'] = $company['name'];

            return [
                'return' => $data,
                'code' => Response::HTTP_OK,
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao buscar dados do usuário: ', [
                'message' => $exception->getMessage(),
                'code-http' => $exception->getCode()
            ]);

            return [
                'return' => [
                    'message' => 'Erro ao buscar dados do usuário.',
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