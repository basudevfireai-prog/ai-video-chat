<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    // User Registration
    public function userRegistration(Request $request)
    {
        try {
            // validation
            $request->validate([
                "name" => "required",
                "email" => "required|email|unique:users",
                // "mobile" => "required|unique:users",
                // Laravel EXPECTS to find 'password_confirmation' here
                "password" => "required|min:8|confirmed",
            ]);

            $user = User::create([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "mobile" => $request->input("mobile"),
                "hear_about_us" => $request->input("hear_about_us"),
                "password" => Hash::make($request->input("password")),
            ]);

            $user->assignRole("user");

            return response()->json(
                [
                    "status" => "success",
                    "message" => "User registered successfully",
                ],
                201,
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => "User registration failed! {$e->getMessage()}",
                ],
                500,
            );
        }
    }

    public function userLogin(Request $request)
    {
        try {
            // validation
            $request->validate([
                "email" => "required|email",
                "password" => "required",
            ]);

            $user = User::where("email", $request->input("email"))->first();

            if (
                $user !== null &&
                Hash::check($request->input("password"), $user->password)
            ) {
                $token = JWTToken::createToken(
                    $request->input("email"),
                    $user->id,
                );

                return response()
                    ->json(
                        [
                            "status" => "success",
                            "message" => "Login successful",
                        ],
                        200,
                    )
                    ->cookie("user_token", $token, 60 * 24 * 30); // 30 days
            }

            return response()->json(
                [
                    "status" => "failed",
                    "message" => "Invalid credentials!",
                ],
                401,
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => "Login failed!",
                ],
                500,
            );
        }
    }

    public function sendOTP(Request $request)
    {
        try {
            // validation
            $request->validate([
                "email" => "required|email",
            ]);

            $otp = rand(1000, 9999);
            $user = User::where("email", $request->input("email"))->first();

            if (!$user) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Email not found!",
                    ],
                    404,
                );
            } else {
                // Send the OTP via email
                Mail::to($user->email)->send(new OTPMail($otp));

                User::where("email", $request->input("email"))->update([
                    "otp" => $otp,
                ]);

                return response()->json(
                    [
                        "status" => "success",
                        "message" =>
                            "4 digit OTP code has been sent to your email",
                    ],
                    200,
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    "status" => "failed",
                    "message" => "Failed to send OTP!",
                ],
                500,
            );
        }
    }

    public function verifyOTP(Request $request)
    {
        try {
            // validation
            $request->validate([
                "email" => "required|email",
                "otp" => "required",
            ]);

            $user = User::where("email", $request->input("email"))->first();

            if (!$user) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Email not found!",
                    ],
                    404,
                );
            } elseif ($user->otp !== $request->input("otp")) {
                return response()->json(
                    [
                        "status" => "failed",
                        "message" => "Invalid OTP!",
                    ],
                    400,
                );
            } else {
                // OTP is valid, proceed with the desired action (e.g., password reset)
                // Clear the OTP after successful verification
                $user->otp = 0;
                $user->save();

                $token = JWTToken::passwordResetToken(
                    $request->input("email"),
                    $user->id,
                );

                return response()
                    ->json(
                        [
                            "status" => "success",
                            "message" => "OTP verified successfully",
                        ],
                        200,
                    )
                    ->cookie("user_token", $token, 10); // Token valid for 10 minutes
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

    public function resetPassword(Request $request)
    {
        try {
            // validation
            $request->validate([
                "password" => "required|min:8|confirmed",
            ]);

            $email = $request->header("email");
            $password = $request->input("password");

            User::where("email", $email)->update([
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

    public function userLogout()
    {
        return redirect("/")->cookie("user_token", "", -1);
    }
}
