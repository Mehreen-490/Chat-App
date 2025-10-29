<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CheckCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => "Invalid Credentials!",
            ], 401);
        }

        $password_match = Hash::check($password, data_get($user, 'password'));
        if (!$password_match) {
            return response()->json([
                'message' => "Invalid Credentials!",
            ], 401);
        }
        $request->setUserResolver(fn() => $user);


        return $next($request);
    }
}
