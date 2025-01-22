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
        Schema::table('patients', function (Blueprint $table) {
            $table->integer('doctor_id')->nullable()->default(0)->change();
            $table->string('nick_name')->nullable();
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->timestamps();
        });
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->integer('question_id');
            $table->string('content');
            $table->timestamps();
        });
        Schema::create('patient_answers', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('question_id');
            $table->integer('answer_id');
            $table->timestamps();
        });
        Schema::create('patient_times', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('patient_id');
            $table->integer('used_time');
            $table->integer('limited_time');
            $table->timestamps();
        });
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('code')->index();
            $table->integer('days');
            $table->integer('minutes');
            $table->dateTime('activated_at')->nullable();
            $table->integer('patient_id')->nullable()->default(0);
            $table->dateTime('end_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->date('end_date');
            $table->integer('days')->nullable()->default(0);
            $table->integer('minutes')->nullable()->default(0);
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promoCodes');
    }
};
