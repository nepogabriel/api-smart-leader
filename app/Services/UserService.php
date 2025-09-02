<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class UserService {
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function register(array $user): array
    {
        try {
            $user = $this->userRepository->register($user);
        
            return [
                'return' => [
                    'message' => 'Usuário Cadastrado com sucesso!',
                    'data' => $user,
                ],
                'code' => Response::HTTP_OK
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao cadastrar usuário: ', [
                'message' => $exception->getMessage(),
                'code-http' => $exception->getCode()
            ]);

            return [
                'return' => [
                    'error' => $exception->getMessage(),
                ],
                'code' => $exception->getCode(),
            ];
        }
    }
}