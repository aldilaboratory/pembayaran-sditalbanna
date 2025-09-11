<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    // app/Models/Transaction.php
    // public function getInvoiceNoAttribute(): string
    // {
    //     return 'INV-'.$this->id;
    // }

    // INV-YYYYMM-000123 (id 6 digit)
    public function getInvoiceCodeAttribute(): string
    {
        $date = ($this->created_at ?? now())->timezone('Asia/Makassar')->format('Ym'); // 202409
        $id   = str_pad((string) $this->id, 6, '0', STR_PAD_LEFT); // 000123
        return "INV-{$date}-{$id}";
    }

}
