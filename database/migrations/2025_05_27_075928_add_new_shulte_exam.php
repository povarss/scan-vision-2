<?php

use App\Models\Exam;
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
        $label = 'Таблиця Шульте';
        $exam = Exam::where('label', $label)->first();
        if (!$exam) {
            $exam = new Exam();
            $exam->label = $label;
            $exam->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
