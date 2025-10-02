<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = StudentClass::all();

        // Pastikan ada minimal 1 angkatan (SchoolYear)
        if (!SchoolYear::query()->exists()) {
            // contoh seed cepat 3 angkatan
            \App\Models\SchoolYear::insert([
                ['school_year' => '2022/2023'],
                ['school_year' => '2023/2024'],
                ['school_year' => '2024/2025'],
            ]);
        }
        $syIds = SchoolYear::pluck('id')->all();

        // (opsional) kalau AcademicYear wajib di DB kamu:
        $ayIds = AcademicYear::query()->exists()
            ? AcademicYear::pluck('id')->all()
            : [];

        foreach ($classes as $class) {
            Student::factory()
                ->count(10)
                ->create([
                    'class_id'         => $class->id,
                    'school_year_id'   => Arr::random($syIds),
                    'academic_year_id' => $ayIds ? Arr::random($ayIds) : null,
                ]);
        }
    }
}
