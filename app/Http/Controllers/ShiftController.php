<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Services\ShiftService;
use App\Http\Requests\Shift\StoreShiftRequest;
use App\Http\Requests\Shift\UpdateShiftRequest;
use App\Http\Resources\ShiftResource;

class ShiftController extends Controller
{

    public function __construct(protected ShiftService $shiftService)
    {
        $this->authorizeResource(Shift::class, 'shift');
    }

    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftRequest $request)
    {
        $shift = $this->shiftService->createShift($request->validated());
        return $this->successResponse(ShiftResource::make($shift), 'Shift created successfully', 201);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $updatedShift = $this->shiftService->updateShift($shift, $request->validated());
        return $this->successResponse(ShiftResource::make($updatedShift), 'Shift updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        $this->shiftService->deleteShift($shift);
        return $this->successResponse(null, 'Shift deleted successfully', 200);
    }
}
