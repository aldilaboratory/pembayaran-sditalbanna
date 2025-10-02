<?php

namespace Database\Factories;

use App\Models\AcademicYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Student::class;

    public function definition(): array
    {
        // Ambil id kelas/angkatan/TA yang ada (kalau kosong, sesuaikan di seeder)
        $classId  = StudentClass::inRandomOrder()->value('id');
        $syId     = SchoolYear::inRandomOrder()->value('id');
        $ayId     = AcademicYear::query()->exists()
                    ? AcademicYear::inRandomOrder()->value('id')
                    : null;

        // NIS 5 digit unik (sesuaikan panjang sesuai kebijakanmu)
        $nis  = str_pad((string) $this->faker->unique()->numberBetween(0, 99999), 5, '0', STR_PAD_LEFT);
        $nama = $this->faker->name();

        return [
            'nama'               => $nama,
            'nis'                => $nis,
            'user_id'            => null,         // akan diisi pada afterMaking (sebelum insert)
            'class_id'           => $classId,
            'school_year_id'     => $syId,
            'academic_year_id'   => null,        // boleh null jika kolom nullable
            'status'             => 'aktif',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Student $student) {
            if ($student->user_id) return;

            $user = User::factory()->create([
                'name'     => $student->nama,
                'username' => $student->nis,            // username = NIS
                'password' => 'siswa'.$student->nis,    // di-hash oleh casts
                'role'     => 'siswa',
            ]);

            $student->user_id = $user->id;             // ← diisi SEBELUM disimpan
        });
    }
}
