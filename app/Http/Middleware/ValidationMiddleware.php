<?php

namespace App\Http\Middleware;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\UserUpdateRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $validation_type): Response
    {
        // auth requests
        if ($validation_type == "signup") {
            app(SignupRequest::class)->validateResolved();
        }
        if ($validation_type == "login") {
            app(LoginRequest::class)->validateResolved();
        }
        if ($validation_type == "company") {
            app(CompanyRequest::class)->validateResolved();
        }
        if ($validation_type == "update_user") {
            app(UserUpdateRequest::class)->validateResolved();
        }
        if ($validation_type == "member") {
            app(MemberRequest::class)->validateResolved();
        }

        // company requests

        return $next($request);
    }
}
