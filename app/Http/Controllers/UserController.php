<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function registrarUsuario(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'rol_id' => 'required|exists:roles,id'
        ]);

        $user = User::create($request->all());

        return response()->json($user, 201);
    }
}
