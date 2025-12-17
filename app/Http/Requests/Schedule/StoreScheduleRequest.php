<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'date' => ['required', 'date', 'unique:schedules,date,NULL,id,department_id,' . $this->department_id],
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
