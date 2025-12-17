<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignScheduleRequest extends FormRequest
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
        $scheduleId = $this->route('schedule')->id;

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('schedule_assignments', 'user_id')->where('schedule_id', $scheduleId), // Cegah assign user yang sama ke schedule ini
            ],
            'shift_id' => ['required', 'exists:shifts,id'],
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
            'user_id.required' => 'ID karyawan wajib diisi.',
            'user_id.exists' => 'ID karyawan tidak ditemukan.',
            'user_id.unique' => 'Karyawan sudah di-assign ke jadwal ini.',
            'shift_id.required' => 'ID shift wajib diisi.',
            'shift_id.exists' => 'ID shift tidak ditemukan.',
        ];
    }
}
