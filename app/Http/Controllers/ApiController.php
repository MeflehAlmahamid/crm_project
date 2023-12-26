<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    public function allCompanies(): JsonResponse
    {
        $companies = Company::with('employees')->get();

        return response()->json(['companies' => $companies]);
    }

    public function getCompany($companyId): JsonResponse
    {
        $company = Company::with('employees')->find($companyId);

        if (!$company) {
            return response()->json(['message' => 'Company not found'], 404);
        }

        $employees = Employee::where('company_id', $companyId)->get();

        return response()->json(['company' => $company, 'employees' => $employees]);
    }
}
