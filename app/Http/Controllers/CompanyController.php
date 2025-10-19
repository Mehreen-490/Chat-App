<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function create(Request $request)
    {
        $user = $request->user();
        $company_name = $request->name;

        $company = Company::store($user, $company_name);

        return response()->json([
            'message' => 'Company created successfully!',
            'data' => [
                'company' => new CompanyResource($company)
            ]
        ], 201);
    }

    public function update(Request $request)
    {
        $company = $request->company;
        $newName = $request->name;

        $company = Company::updateCompanyName($company, $newName);

        return response()->json([
            'message' => 'Company updated successfully!',
            'company' => new CompanyResource($company),
            'auth_user' => $request->auth_user
        ]);
    }

    public function delete(Request $request)
    {
        $company = $request->company;
        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully!'
        ]);
    }

    public function read(Request $request)
    {
        $authUser = $request->user();

        return response()->json([
            'message' => 'Companies fetched successfully!',
            'data' => [
                'companies' => CompanyResource::collection($authUser->companies)
            ]
        ]);
    }
}
