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
    /**
     * @group Departemen
     */
    public function __construct(protected DepartmentService $departmentService)
    {
        $this->authorizeResource(Department::class, 'department');
    }

    /**
     * Menampilkan daftar departemen.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Departments retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "IT"
     *     }
     *   ]
     * }
     */
    public function index(): JsonResponse
    {
        $departments = $this->departmentService->getAllDepartments();
        return $this->successResponse(DepartmentResource::collection($departments), 'Departments retrieved successfully');
    }

    /**
     * Membuat departemen baru.
     *
     * @bodyParam name string required Nama departemen. Contoh: Human Resources
     * @response 201 {
     *   "success": true,
     *   "message": "Department created successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Human Resources"
     *   }
     * }
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->departmentService->createDepartment($request->validated());
        return $this->successResponse(DepartmentResource::make($department), 'Department created successfully', 201);
    }

    /**
     * Menampilkan detail departemen.
     *
     * @urlParam department integer required ID departemen.
     * @response 200 {
     *   "success": true,
     *   "message": "Department retrieved successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "IT"
     *   }
     * }
     */
    public function show(Department $department): JsonResponse
    {
        return $this->successResponse(DepartmentResource::make($department), 'Department retrieved successfully');
    }

    /**
     * Memperbarui departemen.
     *
     * @urlParam department integer required ID departemen.
     * @bodyParam name string Nama departemen baru. Contoh: Finance
     * @response 200 {
     *   "success": true,
     *   "message": "Department updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "Finance"
     *   }
     * }
     */
    public function update(UpdateDepartmentRequest $request, Department $department): JsonResponse
    {
        $updatedDepartment = $this->departmentService->updateDepartment($department, $request->validated());
        return $this->successResponse(DepartmentResource::make($updatedDepartment), 'Department updated successfully');
    }

    /**
     * Menghapus departemen.
     *
     * @urlParam department integer required ID departemen.
     * @response 200 {
     *   "success": true,
     *   "message": "Department deleted successfully"
     * }
     */
    public function destroy(Department $department): JsonResponse
    {
        $this->departmentService->deleteDepartment($department);
        return $this->successResponse(null, 'Department deleted successfully');
    }
}
