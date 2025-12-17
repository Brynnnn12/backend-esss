<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $schedule = $this->route('schedule');
        $departmentId = $this->department_id ?? ($schedule ? $schedule->department_id : '');

        return [
            //pakai sometimes karena bisa saja user hanya mengupdate salah satu field saja
            'department_id' => ['sometimes', 'required', 'exists:departments,id'],
            'date' => ['sometimes', 'required', 'date', 'unique:schedules,date,' . ($schedule ? $schedule->id : '') . ',id,department_id,' . $departmentId],
        ];
    }

    /**
     * Get the body parameters for API documentation.
     */
    public function bodyParameters(): array
    {
        return [
            'department_id' => [
                'description' => 'The ID of the department.',
                'example' => 1,
            ],
            'date' => [
                'description' => 'The date of the schedule in Y-m-d format.',
                'example' => '2023-12-01',
            ],
        ];
    }


    /**
     * Get custom messages for validator errors in bahasa indonesia
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'department_id.required' => 'ID departemen wajib diisi.',
            'department_id.exists' => 'ID departemen tidak ditemukan.',
            'date.required' => 'Tanggal jadwal wajib diisi.',
            'date.date' => 'Tanggal jadwal tidak valid.',
            'date.unique' => 'Jadwal untuk departemen pada tanggal tersebut sudah ada.',
        ];
    }
}
