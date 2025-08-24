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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('school_fee_id')->constrained()->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->string('status', 20)->default('pending');
            $table->string('snap_token')->nullable();
            $table->timestamps();

            // Foreign Key
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('school_fee_id')->references('id')->on('school_fees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
