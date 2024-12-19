<?php

namespace App\Dto;

class ExamResultDto
{
    public function __construct(
        public int $examType,

        public int $correctRemainedCount,
        public int $leftCorrectRemainedCount,
        public int $rightCorrectRemainedCount,

        public int $incorrectCount,
        public int $leftIncorrectClickedCount,
        public int $rightIncorrectClickedCount,
    ) {}
}
