<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class AuthenticatedSessionController extends Controller
{

    /**
     * Masuk ke akun pengguna.
     *
     * @group Otentikasi
     * @unauthenticated
     * @bodyParam email string required Email pengguna. Contoh: john@example.com
     * @bodyParam password string required Kata sandi. Contoh: password123
     * @response 200 {
     *   "success": true,
     *   "message": "Login successful",
     *   "data": {
     *     "user": {
     *       "id": 1,
     *       "name": "John Doe",
     *       "email": "john@example.com"
     *     },
     *     "token": "api_token_here"
     *   }
     * }
     */
    public function store(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->authenticateUser()->load('roles'); // Load roles

        $token = $user->createToken('API Token')->plainTextToken;

        return $this->successResponse([
            new UserResource($user),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Keluar dari akun pengguna.
     *
     * @group Otentikasi
     * @response 200 {
     *   "success": true,
     *   "message": "Logged out successfully"
     * }
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete(); // Revoke token Sanctum

        return $this->successResponse(null, 'Logged out successfully');
    }
}
