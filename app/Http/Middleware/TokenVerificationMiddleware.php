<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isAuthenticated = false;

        if($request->hasCookie('user_token')){

            $token = $request->cookie('user_token');
            $result = JWTToken::VerifyToken($token);

            if ($result != "unauthorized") {
                $request->headers->set('email', $result->userEmail);
                $request->headers->set('id', $result->userID);
                $isAuthenticated = true;
            }

        } elseif ($request->hasCookie('admin_token')) {

            $token = $request->cookie('admin_token');
            $admin_result = JWTToken::VerifyToken($token);

            if ($admin_result != 'unauthorized') {
                $request->headers->set('email', $admin_result->userEmail);
                $request->headers->set('id', $admin_result->userID);
                $isAuthenticated = true;
            }
        }

        // If authenticated, proceed
        if ($isAuthenticated) {
            return $next($request);
        }

        // If not authenticated (no token or unauthorized token), return 401 JSON
        return response()->json([
            'status' => 'failed',
            'message' => 'Unauthorized access. Please log in.',
        ], 403);

    }
}
