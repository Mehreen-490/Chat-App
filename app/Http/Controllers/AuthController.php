<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Http\Resources\UserResource;
use App\Jobs\SendSignupVerificationEmail;
use App\Mail\ResetPasswordEmail;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $user = User::store($request);

        $token = Token::store($user, 'signup_verification_token');
        SendSignupVerificationEmail::dispatch($user, $token)->onQueue('verification_queue');
        return response()->json([
            "message" => "user signup successfully"
        ], 201);
    }

    public function forgotPassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        $token = Token::store($user, 'reset_password_token');

        // // Mail::to($this->user)->send(new SignupVerificationEmail($this->user, $this->token));
        Mail::to($user)->send(new ResetPasswordEmail($user, $token));


        return response()->json([
            'message' => 'Reset Password using token',
            'data' => [
                'token' => $token->token
            ]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $email = data_get($request, 'email');
        $password = data_get($request, 'password');

        $user = User::where('email', $email)->first();

        $user = User::resetPassword($user, $password);

        return response()->json([
            'message' => 'Password reset successfully!'
        ]);
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
