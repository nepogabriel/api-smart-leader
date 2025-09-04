<?php

namespace App\Services;

use App\Repositories\CompanyRepository;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    public function findCompanyById(int $id): mixed
    {
        return $this->companyRepository->findCompanyById($id);
    }

    public function getAllCompanies()
    {
        //
    }
}