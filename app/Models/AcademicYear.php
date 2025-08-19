<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year'
    ];

    public function student() {
        return $this->hasMany(Student::class, 'academic_year_id');
    }
    
    public function schoolFee() {
        return $this->hasMany(SchoolFee::class, 'academic_year_id');
    }
}
