<?php

namespace App\Repositories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Collection;
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

    public function getAllCompanies(): Collection
    {
        $company = Company::all();

        if (!$company)
            throw new \Exception('Nenhuma empresa não encontrada', Response::HTTP_NOT_FOUND);

        return $company;
    }
}