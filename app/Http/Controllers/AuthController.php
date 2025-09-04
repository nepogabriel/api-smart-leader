<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\AuthRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}


    public function login(AuthRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return ApiResponse::response($data['return'], $data['code']);
    }

    public function me(): JsonResponse
    {
        $data = $this->authService->me();

        return ApiResponse::response($data['return'], $data['code']);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return ApiResponse::response(['message' => 'Usuário deslogado com sucesso.']);
    }

    public function refresh(): JsonResponse
    {
        return ApiResponse::response($this->authService->respondWithToken(auth()->refresh()));
    }
}
