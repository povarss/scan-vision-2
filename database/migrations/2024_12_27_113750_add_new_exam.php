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
        $exam = Exam::where('id', 3)->first();
        if (empty($exam)) {
            $exam = new Exam(['id' => 3]);
        }
        $exam->label = 'Пошук символів';
        $exam->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
