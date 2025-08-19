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
        Schema::create('school_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('academic_year_id')->constrained()->onDelete('cascade');
            $table->integer('bulan');
            $table->string('jenis_tagihan');
            $table->decimal('jumlah', 12, 2);
            $table->decimal('sisa', 12, 2);
            $table->date('jatuh_tempo');
            $table->date('tanggal_lunas')->nullable();
            $table->enum('status', ['belum_lunas', 'cicilan', 'lunas'])->default('belum_lunas');
            $table->timestamps();

            // Foreign Key
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('academic_year_id')->references('id')->on('academic_years');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_fees');
    }
};
