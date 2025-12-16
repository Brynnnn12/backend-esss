<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Validation\ValidationException;
use Throwable;
use App\Traits\ApiResponse;

class ApiExceptionHandler
{
    use ApiResponse;
    /**
     * Entry point untuk mendaftarkan exception
     */
    public static function register(Exceptions $exceptions): void
    {
        // Karena ini static method, kita butuh instance untuk panggil trait
        $handler = new self();

        $exceptions->render(function (Throwable $e, Request $request) use ($handler) {
            // Cek apakah request datang dari API?
            if ($request->is('api/*')) {
                return $handler->handleApiException($e);
            }
        });
    }

    /**
     * Logika pemilahan Error
     */
    public function handleApiException(Throwable $e)
    {
        // 1. Error 404 (Not Found) dan tampilakan pesan custom sesuai yang halaman di akses user
        if ($e instanceof NotFoundHttpException) {

            return $this->errorResponse(
                'Halaman yang Anda minta tidak ditemukan.',
                404
            );
        }
        // 2. Error 403 (Access Denied)
        if ($e instanceof AccessDeniedHttpException) {
            return $this->errorResponse(
                'Anda tidak memiliki akses untuk aksi ini.',
                403
            );
        }

        // 3. Error 422 (Validasi)
        if ($e instanceof ValidationException) {
            return $this->errorResponse(
                'Validasi gagal.',
                422,
                $e->errors() // Param ke-3 trait errorResponse
            );
        }

        // 4. General Error (500)
        // Cek environment, jangan show detail di production
        $message = app()->isLocal() ? $e->getMessage() : 'Terjadi kesalahan pada server.';

        return $this->errorResponse($message, 500);
    }
}
