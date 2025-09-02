<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_fee_id',
        'jumlah',
        'status',
        'payment_type',
        'paid_at',
        'canceled_at',
        'expired_at',
        'snap_token',
    ];

    protected $casts = [
        'jumlah'      => 'integer',
        'paid_at'     => 'datetime',
        'canceled_at' => 'datetime',
        'expired_at'  => 'datetime',
    ];

    public function student() {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function schoolFee() {
        return $this->belongsTo(SchoolFee::class, 'school_fee_id', 'id');
    }
}
