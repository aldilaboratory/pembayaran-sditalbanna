<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year_id',
        'bulan',
        'jenis_tagihan',
        'jumlah',
        'sisa',
        'jatuh_tempo',
        'tanggal_lunas',
        'status',
    ];

    protected $casts = [
        'jatuh_tempo' => 'date',
        'tanggal_lunas' => 'date',
        'jumlah' => 'decimal:2',
        'sisa' => 'decimal:2',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function academicYear() {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id', 'id');
    }

    public function transaction() {
        return $this->hasMany(Transaction::class, 'school_fee_id', 'id');
    }

    // Helper method untuk nama bulan
    public function getNamaBulanAttribute() {
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return $bulan[$this->bulan] ?? '';
    }

    // Helper method untuk jenis tagihan
    public function getJenisTagihanLabelAttribute()
    {
        $labels = [
            'spp' => 'SPP',
            'daftar_ulang' => 'Daftar Ulang',
            'biaya_operasional' => 'Biaya Operasional',
        ];
        
        return $labels[$this->jenis_tagihan] ?? $this->jenis_tagihan;
    }

    // Helper untuk format display
    // public function getDisplayPeriodeAttribute() {
    //     return $this->nama_bulan . ' ' . $this->tahun;
    // }

    // Helper untuk format display
    // public function getDisplayPeriodeAttribute() {
    //     return $this->nama_bulan;
    // }
}
