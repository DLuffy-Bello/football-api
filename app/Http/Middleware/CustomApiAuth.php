<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomApiAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('api')->check()) {
            return response()->json([
                'message' => 'No tiene autorización para acceder a este recurso.',
                'error' => 'Unauthorized',
                'debug' => 'Custom middleware response'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
