<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCompanyExists
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $company_id = $request->input('company_id');

        $authUser = $request->user();

        $comapny = Company::where('id', $company_id)
            ->where('creator_id', data_get($authUser, 'id'))
            ->first();
        if (!$comapny) {
            return response()->json([
                'message' => 'Invalid company id given'
            ], 400);
        }

        $request->merge([
            'company' => $comapny,
            'auth_user' => $authUser
        ]);
        return $next($request);
    }
}
