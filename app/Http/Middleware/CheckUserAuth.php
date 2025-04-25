<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::guard('api')->check()) {

            return  response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
                'error' => [
                    'code' => 401,
                    'details' => 'Authentication token is missing or invalid'
                ]
            ], 401);
        }
        return $next($request);
    }
}
