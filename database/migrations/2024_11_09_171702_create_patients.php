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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->integer('doctor_id');
            $table->string('full_name', 400);
            $table->string('phone')->nullable();
            $table->date('born_date')->nullable();
            $table->boolean('gender')->default(1);
            $table->integer('region_id')->nullable();
            $table->string('field')->nullable();
            $table->text('clinic_diagnose')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('label');
        });
        Schema::create('patient_tags', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->string('tag_id');
        });
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->string('key_');
            $table->string('label');
            $table->string('af1')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('patient_tags');
        Schema::dropIfExists('references');
    }
};
