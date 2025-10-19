<?php

namespace App\Http\Middleware;

use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $tokenType): Response
    {
        $token = $request->route('token')
            ?? $request->query('token')
            ?? $request->header('Authorization');
        if (!$token) {
            return response()->json([
                'message' => 'Token missing from request!',
            ], 400);
        }

        $expiryTime = match ($tokenType) {
            'login_token' => Carbon::now()->subDays(2),
            'signup_verification_token' => Carbon::now()->subDays(1),
            default => Carbon::now()->subDays(7)
        };

        Token::where('token_type', $tokenType)
            ->where('created_at', '<', $expiryTime)->delete();


        $tokenRecord = Token::where('token', $token)
            ->where('token_type', $tokenType)
            ->first();


        if (!$tokenRecord && $tokenType == "signup") {
            return response()->json([
                'message' => 'Invalid or expired token'
            ], 400);
        } else if (!$tokenRecord) {
            return response()->json([
                'message' => 'you are not authorized to perform this action'
            ], 401);
        }

        $request->merge([
            'user_id' => data_get($tokenRecord, 'user_id'),
            'token' => $tokenRecord
        ]);



        $request->setUserResolver(fn() => User::find(data_get($tokenRecord, 'user_id')));
        return $next($request);
    }
}
