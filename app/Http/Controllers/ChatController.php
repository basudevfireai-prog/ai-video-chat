<?php

namespace App\Http\Controllers;

use App\Models\SaveTokenUsage;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function sessionStart(Request $request) {
        try {

            $email = $request->header("email");
            $user = User::where("email", $email)->first();

            if($user){
                return Http::post("http://127.0.0.1:8000/api/session/create", $request->all());
            }

            return response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ]);


        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Something went wrong!"
            ]);
        }
    }

    public function aiChatStart(Request $request) {
        try {
            $email = $request->header("email");
            $user = User::where("email", $email)->first();

            if($user){

                $pythonResponse = Http::post("http://127.0.0.1:8000/api/chat", $request->all());

                if (!$pythonResponse->successful()) {

                    return response()->json(['error' => 'Python API failed'], 500);

                } else {

                    // 3. Get the response data (JSON)
                    $data = $pythonResponse->json();

                    // 4. Save the response to DB
                    SaveTokenUsage::updateOrCreate(
            [
                            'user_id' => $user->id,
                            'session_token' => $data['session_token'],
                        ],

                [
                            'user_id' => $user->id,
                            'user_name' => $user->name,
                            'user_email' => $user->email,
                            'chat_duration_seconds' => $data['chat_duration_seconds'] ? (double)($data['chat_duration_seconds'] / 1000) : null,
                            'session_token' => $data['session_token'] ?? null,
                            'prompt_tokens' => $data['prompt_tokens'] ?? null,
                            'completion_tokens' => $data['completion_tokens'] ?? null,
                            'total_tokens' => $data['total_tokens'] ?? null
                        ]
                    );
                }

                return $pythonResponse;

            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'User not found'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Something went wrong! {$e->getMessage()}"
            ]);
        }
    }

    public function sessionPlayed(Request $request) {
        try {
            $email = $request->header("email");
            $user = User::where("email", $email)->first();

            if($user){

                return Http::post("http://127.0.0.1:8000/api/session/{$request->token}/played", $request->all());

            } else {

                return response()->json([
                    'status' => 'failed',
                    'message' => 'User not found'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Something went wrong!"
            ]);
        }
    }

    public function home(Request $request) {
        try {
            $email = $request->header("email");
            $user = User::where("email", $email)->first();

            if($user){
                // return Http::post("http://127.0.0.1:8000/api/session/create", $request->all());
                return Http::get("http://127.0.0.1:8000/");
            } else

            return response()->json([
                'status' => 'failed',
                'message' => 'User not found'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Something went wrong!"
            ]);
        }
    }
}
