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
     * Handle an incoming authentication request.
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
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->user()->currentAccessToken()->delete(); // Revoke token Sanctum

        return $this->successResponse(null, 'Logged out successfully');
    }
}
