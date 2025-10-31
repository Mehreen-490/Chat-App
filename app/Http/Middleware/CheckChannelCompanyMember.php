<?php

namespace App\Http\Middleware;

use App\Models\Channel;
use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class CheckChannelCompanyMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $channelId = data_get($request, 'channel_id');
        $userIds = $request->user_ids;
        $companyId = Channel::where('id', $channelId)->value('company_id');

        $companyUserIds = Company::find($companyId)
            ->users()
            ->whereIn('company_user.user_id', $userIds)
            ->pluck('users.id')
            ->toArray();


        $invalidUserIds = array_diff($userIds, $companyUserIds);

        if (count($invalidUserIds)) {
            throw new HttpResponseException(response()->json([
                'message' => 'Invalid user IDs given: ' . implode(', ', $invalidUserIds)
            ], 400));
        }

        return $next($request);
    }
}
