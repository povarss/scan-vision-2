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
        Schema::table('patient_exams', function (Blueprint $table) {
            $table->integer('time')->nullable()->default(0)->change();
            $table->integer('svg_size')->nullable()->default(0)->change();
            $table->integer('level')->nullable()->default(0)->change();
            $table->integer('mode')->nullable()->default(0)->change();
        });
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
