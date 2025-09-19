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
            'class_id'       => ['required','exists:student_classes,id'],
            'tahun_ajaran'   => ['required','exists:academic_years,id'],
            'jenis_tagihan'  => [
                'required',
                'in:spp,daftar_ulang,biaya_pengembangan,biaya_operasional',
                function ($attribute, $value, $fail) {
                    $this->validateDuplicateTagihan($attribute, $value, $fail);
                }
            ],
            'jumlah'         => ['required','integer','min:1'],
            'bulan'          => ['required','integer','between:1,12'],
            'jatuh_tempo'    => ['required','date'],
            'student_ids'    => ['required','array','min:1'],
            'student_ids.*'  => ['integer','exists:students,id'],
        ];
    }

    /**
     * Validasi untuk mengecek duplikasi tagihan
     */
    private function validateDuplicateTagihan($attribute, $value, $fail)
    {
        $tahunAjaran = $this->input('tahun_ajaran');
        $bulan = $this->input('bulan');
        $studentIds = $this->input('student_ids', []);

        if (!$tahunAjaran || !$bulan || empty($studentIds)) {
            return; // Skip validation jika data tidak lengkap
        }

        // Cek apakah ada tagihan yang sudah ada dengan kombinasi yang sama
        $existingFees = \App\Models\SchoolFee::where('academic_year_id', $tahunAjaran)
            ->where('jenis_tagihan', $value)
            ->where('bulan', $bulan)
            ->whereIn('student_id', $studentIds)
            ->with('student')
            ->get();

        if ($existingFees->isNotEmpty()) {
            $studentNames = $existingFees->pluck('student.name')->unique()->implode(', ');
            $fail("Tagihan dengan jenis '{$value}' untuk bulan {$bulan} pada tahun ajaran yang sama sudah ada untuk siswa: {$studentNames}");
        }
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
            'jumlah.integer' => 'Jumlah tagihan harus berupa angka',
            'jumlah.min' => 'Jumlah tagihan harus lebih dari 0',
            'tahun_ajaran.required' => 'Tahun ajaran wajib dipilih',
            'tahun_ajaran.exists' => 'Tahun ajaran tidak valid',
            'jatuh_tempo.required' => 'Tanggal jatuh tempo wajib diisi',
            'jatuh_tempo.date' => 'Format tanggal jatuh tempo tidak valid',
            'student_ids.required' => 'Pilih minimal satu siswa.',
            'student_ids.min'      => 'Pilih minimal satu siswa.',
        ];
    }
}
