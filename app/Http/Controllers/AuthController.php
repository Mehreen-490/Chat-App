<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Mail\SignupVerificationEmail;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function signup(SignupRequest $req)
    {
        $user = User::store($req);

        $token = Token::store($user, 'signup_verification_token');

        Mail::to($user)->send(new SignupVerificationEmail($user, $token));
        return response()->json([
            "message" => "user signup successfully"
        ], 201);
    }

    public function update(Request $request)
    {
        $profile = $request->file('profile')->store('user_profile');
        $user = User::edit($request, $profile);
        return response()->json([
            "message" => "User updated successfully!",
            'data' => [
                'user' => new UserResource($user),
            ]
        ]);
    }

    public function verifyEmail(Request $req)
    {
        $user = $req->user();
        $user->is_active = true;
        $user->save();

        Token::remove($user, 'signup_verification_token');

        return response()->json([
            'message' => 'Email verified successfully. Your account is now active.'
        ]);
    }

    public function login(Request $req)
    {
        $user = $req->user();

        $token = Token::store($user, 'login_token');

        return response()->json([
            'message' => 'User Logged In Successfully!',
            'data' => [
                'token' =>  data_get($token, 'token'),
                'user' => new UserResource($user)
            ]
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $companies = $user->companies;

        return response()->json([
            'message' => 'user profile fetched successfully',
            'data' => [
                'user' => new UserResource($request->user()),
                'companies' => CompanyResource::collection($companies),
            ]
        ]);
    }

    public function logout(Request $req)
    {
        $token = data_get($req, 'token');

        $token->delete();

        return response()->json([
            'message' => 'Logged Out Successfully!',
        ], 200);
    }
}
