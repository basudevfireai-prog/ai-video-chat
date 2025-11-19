<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function adminDashboard() {
        return view('admin-dashboard');
    }

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
                'message' => "Login failed!",
            ], 500);
        }
    }

    public function sendOtp(Request $request) {
        try {
            // validation
            $request->validate([
                "email" => "required|email",
            ]);

            $otp = rand(1000, 9999);
            $admin = Admin::where("email", $request->input("email"))->first();

            if (!$admin) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Email not found!",
                    ],
                    404,
                );
            } else {
                // Send the OTP via email
                Mail::to($admin->email)->send(new OTPMail($otp));

                Admin::where("email", $request->input("email"))->update([
                    "otp" => $otp,
                ]);

                return response()->json(
                    [
                        "status" => "success",
                        "message" => "OTP sent successfully",
                    ],
                    200,
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => "OTP sending failed!",
                ],
                500,
            );
        }
    }

    public function verifyOtp(Request $request) {
        try {
            // validation
            $request->validate([
                "email" => "required|email",
                "otp" => "required",
            ]);

            $admin = Admin::where("email", $request->input("email"))->first();

            if (!$admin) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Email not found!",
                    ],
                    404,
                );
            } else {
                if ($admin->otp == $request->input("otp")) {
                    return response()->json(
                        [
                            "status" => "success",
                            "message" => "OTP verified successfully",
                        ],
                        200,
                    );
                } else {
                    return response()->json(
                        [
                            "status" => "failed",
                            "message" => "Invalid OTP!",
                        ],
                        400,
                    );
                }
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => "OTP verification failed!",
                ],
                500,
            );
        }
    }

    public function resetPassword(Request $request) {
        try {
            // validation
            $request->validate([
                "password" => "required|min:8|confirmed",
            ]);

            $email = $request->header("email");
            $password = $request->input("password");

            Admin::where("email", $email)->update([
                "password" => Hash::make($password),
            ]);

            return response()->json(
                [
                    "status" => "success",
                    "message" => "Password reset successfully",
                ],
                200,
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => "Password reset failed!",
                ],
                500,
            );
        }
    }

    public function adminLogout() {
        return redirect('/')->cookie('admin_token', '', -1);
    }

}
