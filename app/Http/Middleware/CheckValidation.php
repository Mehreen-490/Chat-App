<?php

namespace App\Http\Middleware;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Requests\ChannelMemberRequest;
use App\Http\Requests\ChannelRequest;
use App\Http\Requests\ChannelUpdateRequest;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\UserUpdateRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckValidation
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
            app(SignupRequest::class);
        }
        if ($validation_type == "login") {
            app(LoginRequest::class);
        }
        if ($validation_type == "company") {
            app(CompanyRequest::class);
        }
        if ($validation_type == "update_user") {
            app(UserUpdateRequest::class);
        }
        if ($validation_type == "member") {
            app(MemberRequest::class);
        }
        if ($validation_type == "channel") {
            app(ChannelRequest::class);
        }
        if ($validation_type == "update_channel") {
            app(ChannelUpdateRequest::class);
        }
        if ($validation_type == "channel_member") {
            app(ChannelMemberRequest::class);
        }

        // company requests

        return $next($request);
    }
}
