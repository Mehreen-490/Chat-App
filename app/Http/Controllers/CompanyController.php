<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{

    public function create(Request $request)
    {
        $user = $request->user();
        $userId = data_get($user, 'id');
        $company_name = $request->name;

        $company = Company::store($user, $company_name);

        $company->users()->attach($userId);

        return response()->json([
            'message' => 'Company created successfully!',
            'data' => [
                'company' => new CompanyResource($company),
                'user' => $user->id
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

    public function assignMember(Request $request)
    {
        $userEmails = $request->user_email;
        $company = data_get($request, 'company');

        // Getting user IDs from emails
        $userIds = User::whereIn('email', $userEmails)->pluck('id');

        $company->users()->syncWithoutDetaching($userIds);

        return response()->json([
            'message' => 'User assigned as member successfully!'
        ]);
    }

    public function removeMember(Request $request)
    {
        $companyId = $request->company_id;
        $userEmails = $request->user_email;

        // Getting company from comapny_id
        $company = data_get($request, 'company');

        //Getting users from user emails
        $userIds = User::whereIn('email', $userEmails)->pluck('id');

        $company->users()->detach($userIds);

        return response()->json([
            'message' => 'Memebers deleted successfully',
            'data' => [
                'companyId' => $companyId,
                'given_users' => $userEmails
            ]
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
