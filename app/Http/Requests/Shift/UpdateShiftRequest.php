<?php

namespace App\Http\Requests\Shift;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
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
        $shift = $this->route('shift');

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:30',
                // Cek Unik tapi abaikan (ignore) ID shift yang sedang diedit
                Rule::unique('shifts')->ignore($shift->id)->where(function ($query) use ($shift) {
                    // Gunakan input user, jika kosong gunakan data lama dari DB
                    // Note: substr($val, 0, 5) digunakan untuk memastikan format DB (08:00:00) 
                    // terbaca sebagai (08:00) agar cocok dengan input user jika dibandingkan manual,
                    // tapi untuk query SQL, MySQL biasanya otomatis handle '08:00' == '08:00:00'.

                    $start = $this->input('start_time', $shift->start_time);
                    $end   = $this->input('end_time', $shift->end_time);

                    return $query->where('start_time', $start)
                        ->where('end_time', $end);
                }),
            ],
            'start_time' => 'sometimes|required|date_format:H:i',
            'end_time'   => 'sometimes|required|date_format:H:i|after:start_time',
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
            'start_time.required' => 'Waktu mulai shift wajib diisi.',
            'start_time.date_format' => 'Waktu mulai shift harus dalam format HH:MM.',
            'end_time.required' => 'Waktu berakhir shift wajib diisi.',
            'end_time.date_format' => 'Waktu berakhir shift harus dalam format HH:MM.',
            'end_time.after' => 'Waktu berakhir shift harus setelah waktu mulai shift.',
        ];
    }
}
