<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\DepartmentService;
use App\Http\Requests\Department\StoreDepartmentRequest;
use App\Http\Requests\Department\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\JsonResponse;

class DepartmentController extends Controller
{

    public function __construct(protected DepartmentService $departmentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $departments = $this->departmentService->getAllDepartments();
        return $this->successResponse(DepartmentResource::collection($departments), 'Departments retrieved successfully');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->departmentService->createDepartment($request->validated());
        return $this->successResponse(DepartmentResource::make($department), 'Department created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department): JsonResponse
    {
        return $this->successResponse(DepartmentResource::make($department), 'Department retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $updatedDepartment = $this->departmentService->updateDepartment($department, $request->validated());
        return $this->successResponse(DepartmentResource::make($updatedDepartment), 'Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): JsonResponse
    {
        $this->departmentService->deleteDepartment($department);
        return $this->successResponse(null, 'Department deleted successfully');
    }
}
