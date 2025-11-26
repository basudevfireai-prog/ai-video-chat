<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminTokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = null;

        // 1) From Cookie
        if ($request->hasCookie('admin_token')) {
            $token = $request->cookie('admin_token');
        }

        // 2) From Authorization: Bearer XXX
        if (!$token && $request->header('Authorization')) {
            $headerToken = $request->header('Authorization');
            if (str_starts_with($headerToken, 'Bearer ')) {
                $token = substr($headerToken, 7);
            }
        }

        // No token
        if (!$token) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized. No token found.',
            ], 401);
        }

        // Validate token
        $result = JWTToken::VerifyToken($token);

        if ($result === "unauthorized") {
            return response()->json([
                'status' => 'failed',
                'message' => 'Unauthorized. Invalid or expired token.',
            ], 401);
        }

        // Attach user info to request
        $request->headers->set("email", $result->userEmail);
        $request->headers->set("id", $result->userID);

        return $next($request);

    }
}
