<?php

namespace App\Http\Controllers;

use App\Models\SaveTokenUsage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function sessionStart(Request $request) {
        $email = $request->header("email");
        $user = User::where("email", $email)->first();

        if($user){
            return Http::post("http://127.0.0.1:8000/api/session/create", $request->all());
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'User not found'
        ]);
    }

    public function aiChatStart(Request $request) {
        $email = $request->header("email");
        $user = User::where("email", $email)->first();

        if($user){
            // return Http::post("http://127.0.0.1:8000/api/session/create", $request->all());
            return Http::post("http://127.0.0.1:8000/api/chat", $request->all());
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'User not found'
        ]);
    }

    public function sessionPlayed(Request $request) {
        $email = $request->header("email");
        $user = User::where("email", $email)->first();

        if($user){
            // return Http::post("http://127.0.0.1:8000/api/session/create", $request->all());
            return Http::post("http://127.0.0.1:8000/api/session/{$request->token}/played", $request->all());
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'User not found'
        ]);
    }

    public function home(Request $request) {
        $email = $request->header("email");
        $user = User::where("email", $email)->first();

        if($user){
            // return Http::post("http://127.0.0.1:8000/api/session/create", $request->all());
            return Http::get("http://127.0.0.1:8000/");
        }

        return response()->json([
            'status' => 'failed',
            'message' => 'User not found'
        ]);
    }

    public function saveTokenUsage(Request $request) {
        $email = $request->header("email");
        $user = User::where("email", $email)->first();

        if($user){

            $request->validate([
                "session_token" => "required",
                "prompt_tokens" => "required",
                "completion_tokens" => "required",
                "total_tokens" => "required",
            ]);

            SaveTokenUsage::create([
                "user_id" => $user->id,
                "user_name" => $user->name,
                "user_email" => $user->email,
                "session_token" => $request->input("session_token"),
                "prompt_tokens" => $request->input("prompt_tokens"),
                "completion_tokens" => $request->input("completion_tokens"),
                "total_tokens" => $request->input("total_tokens"),
            ]);

        }

        return response()->json([
            'status' => 'failed',
            'message' => 'Unauthorized user! Please login.'
        ]);
    }
}
