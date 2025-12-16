<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Get the authenticated user's profile.
     */
    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user()->load('roles');

        return $this->successResponse(new UserResource($user), 'User Berhasil Diambil', 200);
    }
}
