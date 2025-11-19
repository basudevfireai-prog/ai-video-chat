<?php

namespace App\Http\Controllers;

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
}
