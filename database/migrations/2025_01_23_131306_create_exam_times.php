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
        Schema::create('exam_times', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('patient_exam_id');
            $table->integer('counter');
            $table->dateTime('started_at');
            $table->dateTime('counted_at');
            $table->dateTime('finished_at')->nullable();
            $table->integer('used_seconds')->nullable()->default(0);
            $table->timestamps();
        });
        Schema::table('patient_exams', function (Blueprint $table) {
            $table->integer('counter')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_times');
    }
};
