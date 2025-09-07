<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolFeesRequest extends FormRequest
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
            'class_id' => 'required|exists:student_classes,id',
            'tahun_ajaran' => 'required|exists:academic_years,id',
            'bulan' => 'required|integer|between:1,12',
            'jenis_tagihan' => 'required|string|in:spp,daftar_ulang,biaya_pengembangan,biaya_operasional',
            'jumlah' => 'required|numeric|min:0',
            'jatuh_tempo' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'class_id.required' => 'Kelas wajib dipilih',
            'class_id.exists' => 'Kelas tidak valid',
            'bulan.required' => 'Bulan wajib dipilih',
            'bulan.between' => 'Bulan tidak valid',
            'jenis_tagihan.required' => 'Jenis tagihan wajib dipilih',
            'jenis_tagihan.in' => 'Jenis tagihan tidak valid',
            'jumlah.required' => 'Jumlah tagihan wajib diisi',
            'jumlah.numeric' => 'Jumlah tagihan harus berupa angka',
            'jumlah.min' => 'Jumlah tagihan tidak boleh negatif',
            'jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi',
            'jatuh_tempo.date' => 'Format tanggal jatuh tempo tidak valid',
        ];
    }
}
