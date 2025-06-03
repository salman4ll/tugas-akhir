<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleApi
{
    /**
     * Handle an incoming API request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('sanctum')->user();

        if (!$user || !$user->role || !in_array($user->role->nama, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. You do not have the required role.'
            ], 403);
        }

        return $next($request);
    }
}
