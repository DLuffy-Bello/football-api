<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'email_verified_at' => now(),
            ])->assignRole('viewer');

            $token = $user->createToken('Football App Token')->accessToken;

            return response()->json([
                'message' => 'User registered successfully',
                'data' => [
                    'user' => $user->getApiData(),
                    'token' => $token,
                ]
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();

            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('Football App Token')->accessToken;

                return response()->json([
                    'message' => 'Login successful',
                    'data' => [
                        'user' => $user->getApiData(),
                        'token' => $token,
                    ]
                ]);
            }

            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
         try {
            $user = $request->user();
            $tokenId = $user->token()->id;

            // Buscar y revocar el token
            $token = Token::find($tokenId);
            if ($token) {
                $token->revoke();
            }
            return response()->json([
                'message' => 'Successfully logged out'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error during logout',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
