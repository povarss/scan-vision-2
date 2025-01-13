<?php

use App\Models\Exam;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $exam = Exam::where('id', 1)->first();
        if (!empty($exam)) {
            $exam->label = 'Неглект тест';
            $exam->save();
        }

        $exam = Exam::where('id', 2)->first();
        if (!empty($exam)) {
            $exam->label = 'Пошук обʼєктів';
            $exam->save();
        }
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
