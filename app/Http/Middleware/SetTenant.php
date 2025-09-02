<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($user = $request->user()) {
            app()->instance('tenant_id', $user->company_id);
        }

        return $next($request);
    }
}
