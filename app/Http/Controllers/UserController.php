<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;


class UserController extends Controller
{

    /**
     * Mendapatkan profil pengguna yang sedang login.
     *
     * @group Otentikasi
     * @response 200 {
     *   "success": true,
     *   "message": "User Berhasil Diambil",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com",
     *     "roles": [
     *       {
     *         "name": "Employee"
     *       }
     *     ]
     *   }
     * }
     */
    public function me(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user()->load('roles');

        return $this->successResponse(new UserResource($user), 'User Berhasil Diambil', 200);
    }
}
