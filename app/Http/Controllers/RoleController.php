<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Contracts\Role;

class RoleController extends Controller
{
    public function createRole(Request $request) {

        $request->validate([
            'role' => 'required|string'
        ]);

        Role::create(['name' => $request->input('role')]);

        return response()->json([
            'status' => 'success',
            'message' => 'Role created successfully'
        ], 201);

    }
}
