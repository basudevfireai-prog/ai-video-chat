<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function adminLogin(Request $request) {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $admin = Admin::where('email', $request->input('email'))->first();

            if ($admin && Hash::check($request->input('password'), $admin->password) && $admin->hasRole('admin')) {
                $token = JWTToken::createToken(
                    $request->input('email'),
                    $admin->id,
                );

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                ], 200)->cookie('admin_token', $token, 60 * 24 * 30); // 30 days
            }
        }catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Login failed!',
            ], 500);
        }
    }

    public function adminLogout() {
        return redirect('/')->cookie('admin_token', '', -1);
    }

}
