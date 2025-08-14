<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Data wajib
            $table->string('nama');
            $table->string('nis')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('school_year_id');
            $table->unsignedBigInteger('academic_year_id');

            // Data opsional (di-input belakangan)
            $table->string('nik')->unique()->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('agama')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('tinggal_dengan')->nullable();
            $table->string('nama_ibu_kandung')->nullable();
            $table->string('nama_ayah_kandung')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('nomor_whatsapp_orang_tua_wali')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('class_id')->references('id')->on('student_classes');
            $table->foreign('school_year_id')->references('id')->on('school_years');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
