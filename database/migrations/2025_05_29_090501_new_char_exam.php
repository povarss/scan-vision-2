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
        $label = 'Пошук літери';
        $exam = Exam::where('label', $label)->first();
        if (!$exam) {
            $exam = new Exam();
            $exam->label = $label;
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
