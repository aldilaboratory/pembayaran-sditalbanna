<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentsRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'nis' => 'required|string|max:20|unique:students,nis|unique:users,username',
            'class' => 'required|exists:student_classes,class',
            'school_year' => 'required|exists:school_years,school_year',
            'academic_year' => 'required|exists:academic_years,academic_year',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama siswa wajib diisi',
            'nis.required' => 'NIS wajib diisi',
            'nis.unique' => 'NIS sudah terdaftar dalam sistem',
            'class.required' => 'Kelas harus dipilih',
            'class.exists' => 'Kelas yang dipilih tidak valid',
            'school_year.required' => 'Angkatan harus dipilih',
            'school_year.exists' => 'Angkatan yang dipilih tidak valid',
            'academic_year.required' => 'Tahun ajaran harus dipilih',
            'academic_year.exists' => 'Tahun ajaran yang dipilih tidak valid',
        ];
    }
}
