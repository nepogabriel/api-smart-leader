<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Services\CompanyService;

class CompanyController extends Controller
{
    public function __construct(
        private CompanyService $companyService
    ) {}

    public function getAllCompanies()
    {
        $data = $this->companyService->getAllCompanies();

        return ApiResponse::response($data['return'], $data['code']);
    }
}
