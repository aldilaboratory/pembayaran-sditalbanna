<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $fillable = [
        'id',
        'student_id',
        'school_fee_id',
        'jumlah',
        'status',
        'snap_token',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function schoolFee() {
        return $this->belongsTo(SchoolFee::class, 'school_fee_id', 'id');
    }

    const STATUS_PENDING = 'pending';
    const STATUS_SUCCESS = 'success';
    const STATUS_EXPIRED = 'expired';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELED = 'canceled';
}
