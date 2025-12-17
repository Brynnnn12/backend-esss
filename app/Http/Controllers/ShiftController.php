<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\ShiftService;
use App\Http\Requests\Shift\StoreShiftRequest;
use App\Http\Requests\Shift\UpdateShiftRequest;
use App\Http\Resources\ShiftResource;

class ShiftController extends Controller
{
    /**
     * @group Shift
     */
    public function __construct(protected ShiftService $shiftService)
    {
        $this->authorizeResource(Shift::class, 'shift');
    }

    /**
     * Menampilkan daftar shift.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Shifts retrieved successfully",
     *   "data": [
     *     {
     *       "id": "uuid",
     *       "name": "Morning Shift",
     *       "start_time": "08:00",
     *       "end_time": "16:00"
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $shifts = $this->shiftService->getAllShifts();
        return $this->successResponse(ShiftResource::collection($shifts), 'Shifts retrieved successfully', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Membuat shift baru.
     *
     * @bodyParam name string required Nama shift. Contoh: Morning Shift
     * @bodyParam start_time string required Waktu mulai (HH:MM). Contoh: 08:00
     * @bodyParam end_time string required Waktu berakhir (HH:MM). Contoh: 16:00
     * @response 201 {
     *   "success": true,
     *   "message": "Shift created successfully",
     *   "data": {
     *     "id": "uuid",
     *     "name": "Morning Shift",
     *     "start_time": "08:00",
     *     "end_time": "16:00"
     *   }
     * }
     */
    public function store(StoreShiftRequest $request)
    {
        $shift = $this->shiftService->createShift($request->validated());
        return $this->successResponse(ShiftResource::make($shift), 'Shift created successfully', 201);
    }

    /**
     * Menampilkan detail shift.
     *
     * @urlParam shift string required ID shift (UUID).
     * @response 200 {
     *   "success": true,
     *   "message": "Shift retrieved successfully",
     *   "data": {
     *     "id": "uuid",
     *     "name": "Morning Shift",
     *     "start_time": "08:00",
     *     "end_time": "16:00"
     *   }
     * }
     */
    public function show(Shift $shift)
    {
        return $this->successResponse(ShiftResource::make($shift), 'Shift retrieved successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        //
    }

    /**
     * Memperbarui shift.
     *
     * @urlParam shift string required ID shift (UUID).
     * @bodyParam name string Nama shift baru. Contoh: Afternoon Shift
     * @bodyParam start_time string Waktu mulai baru (HH:MM). Contoh: 14:00
     * @bodyParam end_time string Waktu berakhir baru (HH:MM). Contoh: 22:00
     * @response 200 {
     *   "success": true,
     *   "message": "Shift updated successfully",
     *   "data": {
     *     "id": "uuid",
     *     "name": "Afternoon Shift",
     *     "start_time": "14:00",
     *     "end_time": "22:00"
     *   }
     * }
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $updatedShift = $this->shiftService->updateShift($shift, $request->validated());
        return $this->successResponse(ShiftResource::make($updatedShift), 'Shift updated successfully', 200);
    }

    /**
     * Menghapus shift.
     *
     * @urlParam shift string required ID shift (UUID).
     * @response 200 {
     *   "success": true,
     *   "message": "Shift deleted successfully"
     * }
     */
    public function destroy(Shift $shift)
    {
        $this->shiftService->deleteShift($shift);
        return $this->successResponse(null, 'Shift deleted successfully', 200);
    }
}
