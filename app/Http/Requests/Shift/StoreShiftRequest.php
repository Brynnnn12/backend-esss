<?php

namespace App\Http\Requests\Shift;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreShiftRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:30',
                Rule::unique('shifts')->where(function ($query) {
                    return $query->where('start_time', $this->start_time)
                        ->where('end_time', $this->end_time);
                }),
                'regex:/^[^<>]*$/',
                'not_regex:/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|ALTER|TRUNCATE|EXEC|UNION|CREATE|REPLACE|RENAME|GRANT|REVOKE)\b)/i',
            ],
            // Format H:i (Contoh: 08:00)
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
        ];
    }


    /**
     * Get custom messages for validator errors.pakai bahasa indonesia
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama shift wajib diisi.',
            'name.string' => 'Nama shift harus berupa teks.',
            'name.max' => 'Nama shift tidak boleh lebih dari 30 karakter.',
            'name.unique' => 'Kombinasi nama, waktu mulai, dan waktu berakhir shift sudah ada.',
            'name.regex' => 'Nama shift mengandung karakter terlarang.',
            'name.not_regex' => 'Nama shift mengandung kata terlarang.',
            'start_time.required' => 'Waktu mulai shift wajib diisi.',
            'start_time.date_format' => 'Waktu mulai shift harus dalam format HH:MM.',
            'end_time.required' => 'Waktu berakhir shift wajib diisi.',
            'end_time.date_format' => 'Waktu berakhir shift harus dalam format HH:MM.',
            'end_time.after' => 'Waktu berakhir shift harus setelah waktu mulai shift.',
        ];
    }
}
