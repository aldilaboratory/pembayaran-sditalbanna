<?php

namespace App\Http\Controllers\Admin\Data_Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentClassesRequest;
use App\Http\Requests\StoreStudentsRequest;
use App\Http\Requests\UpdateStudentsRequest;
use App\Models\AcademicYear;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DataSiswaAdminController extends Controller
{
    public function index() {
        $students = Student::with(['studentClass', 'schoolYear', 'academicYear'])->get();

        return view('admin.data_siswa.index', compact('students'));
    }

    public function create() {
        $studentClasses = StudentClass::all();
        $schoolYears = SchoolYear::all();
        $academicYears = AcademicYear::all();

        return view('admin.data_siswa.create', compact('studentClasses', 'schoolYears', 'academicYears'));
    }

    public function store(StoreStudentsRequest $request) {
        DB::beginTransaction();

        try {
            // Mencari ID berdasarkan value yang dipilih
            $classId = StudentClass::where('class', $request->class)->first()->id;
            $schoolYearId = SchoolYear::where('school_year', $request->school_year)->first()->id;
            $academicYearId = AcademicYear::where('academic_year', $request->academic_year)->first()->id;

            if (!$classId) {
                throw new \Exception('Kelas "' . $request->class . '" tidak ditemukan');
            }
            if (!$schoolYearId) {
                throw new \Exception('School Year "' . $request->school_year . '" tidak ditemukan');
            }
            if (!$academicYearId) {
                throw new \Exception('Academic Year "' . $request->academic_year . '" tidak ditemukan');
            }

            // Generate data User dengan username menggunakan NIS
            $user = User::create([
                'name' => $request->name,
                'username' => $request->nis,
                'password' => Hash::make($this->generateDefaultPassword($request->nis)),
                'role' => 'siswa',
            ]);

            // Buat data Student dengan user_id yang barusan dibuat
            $student = Student::create ([
                'nama' => $request->name,
                'nis' => $request->nis,
                'user_id' => $user->id, // Auto binding ke user
                'class_id' => $classId,
                'school_year_id' => $schoolYearId,
                'academic_year_id' => $academicYearId,
            ]);

            DB::commit();

            return redirect()->route('admin.data_siswa')
                             ->with('success', 'Data siswa berhasil ditambahkan')
                             ->with('login_credentials', [
                                'username' => $user->username,
                                'password' => $this->generateDefaultPassword($request->nis),
                                'student_name' => $student->nama,
                             ]);

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal menambahkan data siswa: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $students = Student::with('user')->findOrFail($id);

        $studentClasses = StudentClass::all();
        $schoolYears = SchoolYear::all();
        $academicYears = AcademicYear::all();

        return view('admin.data_siswa.edit', compact('students', 'studentClasses', 'schoolYears', 'academicYears'));
    }

    public function update(UpdateStudentsRequest $request, $id) {
        $students = Student::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            $classId = StudentClass::where('class', $request->class)->first()->id;
            $schoolYearId = SchoolYear::where('school_year', $request->school_year)->first()->id;
            $academicYearId = AcademicYear::where('academic_year', $request->academic_year)->first()->id;

            if (!$classId) {
                throw new \Exception('Kelas "' . $request->class . '" tidak ditemukan');
            }
            if (!$schoolYearId) {
                throw new \Exception('School Year "' . $request->school_year . '" tidak ditemukan');
            }
            if (!$academicYearId) {
                throw new \Exception('Academic Year "' . $request->academic_year . '" tidak ditemukan');
            }

            // Update data User (sinkronisasi username dengan NIS)
            $students->user->update([
                'name' => $request->name,
                'username' => $request->nis, // Username ikut berubah sesuai NIS baru
                'password' => Hash::make($this->generateDefaultPassword($request->nis)), // Set password baru dengan format "siswa + 4 digit terakhir NIS" 
            ]);

            // Update data Siswa
            $students->update([
                'nama' => $request->name,
                'nis' => $request->nis,
                'class_id' => $classId,
                'school_year_id' => $schoolYearId,
                'academic_year_id' => $academicYearId,
            ]);

            DB::commit();

            return redirect()->route('admin.data_siswa')
                             ->with('success', 'Data siswa berhasil diubah! Username telah diperbarui sesuai NIS terbaru.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Gagal mengubah data siswa: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        $student = Student::with('user')->findOrFail($id);

        DB::beginTransaction();

        try {
            $studentName = $student->nama;
            $studentNis = $student->nis;

            // Hapus data user dulu karena ada foreign key yang nyangkut/terhubung
            if ($student->user) {
                $student->user->delete();
            }

            // Kemudian sudah bisa hapus data siswanya
            $student->delete();

            DB::commit();

            return redirect()->route('admin.data_siswa')
                         ->with('success', "Data siswa {$studentName} (NIS: {$studentNis}) berhasil dihapus!");
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                             ->with('error', 'Gagal menghapus data siswa: ' . $e->getMessage());
        }
    }

    // Method untuk generate password default
    private function generateDefaultPassword($nis)
    {
        // Format: siswa + 6 digit terakhir NIS
        return 'siswa' . substr($nis, -6);
    }
}
