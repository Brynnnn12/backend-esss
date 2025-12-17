<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Services\ScheduleService;
use App\Http\Resources\ScheduleResource;
use App\Services\ScheduleAssignmentService;
use App\Http\Resources\ScheduleAssignmentResource;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\AssignScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * @group Jadwal
     */
    public function __construct(protected ScheduleService $scheduleService, protected ScheduleAssignmentService $assignmentService)
    {
        $this->authorizeResource(Schedule::class, 'schedule');
    }

    /**
     * Menampilkan daftar jadwal.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Schedules retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "department_id": 1,
     *       "date": "2023-12-01",
     *       "department": {
     *         "id": 1,
     *         "name": "IT"
     *       }
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $schedules = $this->scheduleService->getAllSchedules();
        return $this->successResponse(ScheduleResource::collection($schedules), 'Schedules retrieved successfully', 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Membuat jadwal baru.
     *
     * @bodyParam department_id integer required ID departemen. Contoh: 1
     * @bodyParam date string required Tanggal jadwal (Y-m-d). Contoh: 2023-12-01
     * @response 201 {
     *   "success": true,
     *   "message": "Schedule created successfully",
     *   "data": {
     *     "id": 1,
     *     "department_id": 1,
     *     "date": "2023-12-01",
     *     "department": {
     *       "id": 1,
     *       "name": "IT"
     *     }
     *   }
     * }
     */
    public function store(StoreScheduleRequest $request)
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());
        return $this->successResponse(new ScheduleResource($schedule->load('department')), 'Schedule created successfully', 201);
    }

    /**
     * Menampilkan detail jadwal.
     *
     * @urlParam schedule integer required ID jadwal.
     * @response 200 {
     *   "success": true,
     *   "message": "Schedule retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "department_id": 1,
     *     "date": "2023-12-01",
     *     "department": {
     *       "id": 1,
     *       "name": "IT"
     *     }
     *   }
     * }
     */
    public function show(Schedule $schedule)
    {
        return $this->successResponse(new ScheduleResource($schedule->load('department')), 'Schedule retrieved successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Memperbarui jadwal.
     *
     * @urlParam schedule integer required ID jadwal.
     * @bodyParam department_id integer ID departemen baru. Contoh: 2
     * @bodyParam date string Tanggal jadwal baru (Y-m-d). Contoh: 2023-12-02
     * @response 200 {
     *   "success": true,
     *   "message": "Schedule updated successfully",
     *   "data": {
     *     "id": 1,
     *     "department_id": 2,
     *     "date": "2023-12-02",
     *     "department": {
     *       "id": 2,
     *       "name": "HR"
     *     }
     *   }
     * }
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $updatedSchedule = $this->scheduleService->updateSchedule($schedule, $request->validated());
        return $this->successResponse(new ScheduleResource($updatedSchedule->load('department')), 'Schedule updated successfully', 200);
    }

    /**
     * Menghapus jadwal.
     *
     * @urlParam schedule integer required ID jadwal.
     * @response 200 {
     *   "success": true,
     *   "message": "Schedule deleted successfully"
     * }
     */
    public function destroy(Schedule $schedule)
    {
        $this->scheduleService->deleteSchedule($schedule);
        return $this->successResponse(null, 'Schedule deleted successfully', 200);
    }

    /**
     * Menugaskan karyawan ke jadwal.
     *
     * @urlParam schedule integer required ID jadwal.
     * @bodyParam user_id integer required ID karyawan. Contoh: 1
     * @bodyParam shift_id string required ID shift (UUID). Contoh: uuid-shift
     * @response 201 {
     *   "success": true,
     *   "message": "Employee assigned to schedule successfully",
     *   "data": {
     *     "id": 1,
     *     "schedule_id": 1,
     *     "user_id": 1,
     *     "shift_id": "uuid-shift"
     *   }
     * }
     */
    public function assign(AssignScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('create', \App\Models\ScheduleAssignment::class); // Explicit authorize
        $data = array_merge($request->validated(), ['schedule_id' => $schedule->id]);
        $assignment = $this->assignmentService->assignEmployee($data);
        return $this->successResponse(new ScheduleAssignmentResource($assignment->load(['schedule.department', 'user', 'shift'])), 'Employee assigned to schedule successfully', 201);
    }

    /**
     * Menampilkan jadwal pribadi karyawan.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "My schedule retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "schedule_id": 1,
     *       "user_id": 1,
     *       "shift_id": "uuid-shift",
     *       "date": "2023-12-01",
     *       "shift": {
     *         "id": "uuid-shift",
     *         "name": "Morning Shift"
     *       }
     *     }
     *   ]
     * }
     */
    public function mySchedule()
    {
        $assignments = $this->assignmentService->getAssignmentsByUser(Auth::id());
        return $this->successResponse(ScheduleAssignmentResource::collection($assignments), 'My schedule retrieved successfully', 200);
    }

    /**
     * Menampilkan daftar penugasan untuk jadwal tertentu.
     *
     * @urlParam schedule integer required ID jadwal.
     * @response 200 {
     *   "success": true,
     *   "message": "Schedule assignments retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "schedule_id": 1,
     *       "user_id": 1,
     *       "shift_id": "uuid-shift",
     *       "user": {
     *         "id": 1,
     *         "name": "John Doe"
     *       },
     *       "shift": {
     *         "id": "uuid-shift",
     *         "name": "Morning Shift"
     *       }
     *     }
     *   ]
     * }
     */
    public function assignments(Schedule $schedule)
    {
        $this->authorize('view', $schedule); // HR bisa view schedule
        $assignments = $this->assignmentService->getAssignmentsBySchedule($schedule->id);
        return $this->successResponse(ScheduleAssignmentResource::collection($assignments), 'Schedule assignments retrieved successfully', 200);
    }
}
