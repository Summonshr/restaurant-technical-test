<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {

        if (Auth::attempt($request->validated())) {
            return response()->json(['token' => auth()->user()->createToken('auth-token')->plainTextToken]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function getUser(Request $request)
    {
        return $request->user();
    }
}
