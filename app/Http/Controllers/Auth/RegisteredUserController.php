<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Mendaftarkan pengguna baru.
     *
     * @group Otentikasi
     * @unauthenticated
     * @bodyParam name string required Nama pengguna. Contoh: John Doe
     * @bodyParam email string required Email pengguna (harus unik). Contoh: john@example.com
     * @bodyParam password string required Kata sandi (minimal 8 karakter). Contoh: password123
     * @bodyParam password_confirmation string required Konfirmasi kata sandi. Contoh: password123
     * @response 201 {
     *   "success": true,
     *   "message": "User registered successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "email": "john@example.com"
     *   }
     * }
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated, &$user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // event(new Registered($user)); // Remove for API

            // Assign role default
            $user->assignRole('employee');
        });


        return $this->successResponse([
            'user' => new UserResource($user),
        ], 'Registration successful', 201);
    }
}
