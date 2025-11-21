<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function createRole(Request $request) {
        try {
            $email = $request->header("email");
            $admin = Admin::where("email", $email)->first();

            if($admin){

                $request->validate([
                    'role' => 'required|string'
                ]);

                Role::create(['name' => $request->input('role')]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Role created successfully'
                ], 201);

            } else {

                return response()->json([
                    'status' => 'failed',
                    'message' => 'Please, login as an admin!'
                ]);
            }

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => "Something went wrong!"
            ]);
        }

    }
}
