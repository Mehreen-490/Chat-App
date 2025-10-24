<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\User;
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

    public function assignMember(Request $request)
    {
        $companyId = $request->company_id;
        $userEmails = $request->user_email;
        $company = Company::findOrFail($companyId);

        // Getting user IDs from emails
        $userIds = User::whereIn('email', $userEmails)->pluck('id', 'email')->toArray();

        // Getting existing user/member IDs
        $existingUserIds = $company->users()->pluck('users.id')->toArray();

        //Determinig new and existing users
        $alreadyUsers = [];
        $newUserIds = [];
        $newUserEmails = [];

        foreach ($userIds as $email => $id) {
            if (in_array($id, $existingUserIds)) {
                $alreadyUsers[] = $email;
            } else {
                $newUserIds[] = $id;
                $newUserEmails[] = $email;
            }
        }

        if (!empty($newUserIds)) {
            $company->users()->attach(array_values($newUserIds));
        }

        return response()->json([
            'message' => 'User assigned as member successfully!',
            'company_id' => $companyId,
            'given_user' => $userIds,
            'already_user' => $alreadyUsers,
            'new_users' => $newUserEmails


        ]);
    }

    public function removeMember(Request $request)
    {
        $companyId = $request->company_id;
        $userEmails = $request->user_email;

        // Getting company from comapny_id
        $company = Company::findOrFail($companyId);

        //Getting users from user emails
        $userIds = User::whereIn('email', $userEmails)->pluck('id', 'email')->toArray();

        //Getting existing users/members
        $existingUserIds = $company->users()->pluck('users.id')->toArray();

        $toRemoveIds = [];
        $removed = [];
        $notFound = [];

        foreach ($userIds as $email => $id) {
            if (in_array($id, $existingUserIds)) {
                $toRemoveIds[] = $id;
                $removed[] = $email;
            } else {
                $notFound[] = $email;
            }
        }

        if (!empty($toRemoveIds)) {
            $company->users()->detach($toRemoveIds);
        }

        return response()->json([
            'message' => 'Memebers deleted successfully',
            'data' => [
                'companyId' => $companyId,
                'given_users' => $userEmails,
                'removed_users' => $removed,
                'not_found' => $notFound
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
