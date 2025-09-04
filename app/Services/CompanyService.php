<?php

namespace App\Services;

use App\Repositories\CompanyRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    public function findCompanyById(int $id): mixed
    {
        return $this->companyRepository->findCompanyById($id);
    }

    public function getAllCompanies(): array
    {
        try {
            $companies = $this->companyRepository->getAllCompanies();
        
            return [
                'return' => [
                    'data' => $companies,
                ],
                'code' => Response::HTTP_OK
            ];
        } catch (\Exception $exception) {
            Log::error('Erro ao buscar todas as empresas: ', [
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