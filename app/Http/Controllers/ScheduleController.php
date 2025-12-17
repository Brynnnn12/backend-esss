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

    public function __construct(protected ScheduleService $scheduleService, protected ScheduleAssignmentService $assignmentService)
    {
        $this->authorizeResource(Schedule::class, 'schedule');
    }
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleRequest $request)
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());
        return $this->successResponse(new ScheduleResource($schedule->load('department')), 'Schedule created successfully', 201);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(UpdateScheduleRequest $request, Schedule $schedule)
    {
        $updatedSchedule = $this->scheduleService->updateSchedule($schedule, $request->validated());
        return $this->successResponse(new ScheduleResource($updatedSchedule->load('department')), 'Schedule updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $this->scheduleService->deleteSchedule($schedule);
        return $this->successResponse(null, 'Schedule deleted successfully', 200);
    }

    /**
     * Assign employee to schedule.
     */
    public function assign(AssignScheduleRequest $request, Schedule $schedule)
    {
        $this->authorize('create', \App\Models\ScheduleAssignment::class); // Explicit authorize
        $data = array_merge($request->validated(), ['schedule_id' => $schedule->id]);
        $assignment = $this->assignmentService->assignEmployee($data);
        return $this->successResponse(new ScheduleAssignmentResource($assignment->load(['schedule.department', 'user', 'shift'])), 'Employee assigned to schedule successfully', 201);
    }

    /**
     * Get current user's schedule assignments.
     */
    public function mySchedule()
    {
        $assignments = $this->assignmentService->getAssignmentsByUser(Auth::id());
        return $this->successResponse(ScheduleAssignmentResource::collection($assignments), 'My schedule retrieved successfully', 200);
    }
}
