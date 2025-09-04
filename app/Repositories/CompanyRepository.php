<?php

namespace App\Repositories;

use App\Models\Company;
use Symfony\Component\HttpFoundation\Response;

class CompanyRepository
{
    public function findCompanyById(int $id): mixed
    {
        $company = Company::query()
            ->select('name')
            ->where('id', '=', $id)
            ->first();

        if (!$company)
            throw new \Exception('Empresa não encontrada', Response::HTTP_NOT_FOUND);

        return $company;
    }
}