<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nis',
        'user_id',
        'class_id',
        'school_year_id',
        'academic_year_id',
        'nik',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'alamat',
        'kewarganegaraan',
        'tinggal_dengan',
        'nama_ibu_kandung',
        'nama_ayah_kandung',
        'nama_wali',
        'nomor_whatsapp_orang_tua_wali',
        'status',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function studentClass() {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function schoolYear() {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function academicYear() {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function schoolFee() {
        return $this->hasMany(SchoolFee::class, 'student_id', 'id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'student_id', 'id');
    }

    public function pendingTransactions() {
        return $this->transactions()->where('status', 'pending');
    }

    // Normalisasi nomor WA (hilangkan non-digit, leading 0 -> 62)
    public function getWhatsappTargetAttribute(): ?string
    {
        $raw = $this->nomor_whatsapp_orang_tua_wali;
        if (!$raw) return null;
        $digits = preg_replace('/\D+/', '', $raw);
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }
        return $digits;
    }
}