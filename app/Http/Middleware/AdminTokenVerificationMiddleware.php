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

        // // Check user token
        // if ($request->hasCookie('user_token')) {
        //     $token = $request->cookie('user_token');
        // }

        // Check admin token
        if ($request->hasCookie('admin_token')) {
            $token = $request->cookie('admin_token');
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
