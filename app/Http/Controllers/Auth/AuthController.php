<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('username', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            \Log::error('Login failed', ['credentials' => $credentials]);
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password Salah',
            ], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
        ], 200);
    }
}
