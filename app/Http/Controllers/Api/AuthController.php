<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function register(Request $request, AuthService $service)
    {
        $data = $service->register(
            $request->only('name', 'email', 'password')
        );

        return response()->json([
            'user' => $data['user'],
            'token' => $data['token'],
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request, AuthService $service)
    {
        $data = $service->login(
            $request->only('email', 'password')
        );

        if (!$data) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'user' => $data['user'],
            'token' => $data['token'],
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request, AuthService $service)
    {
        $service->logout($request->user());

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}