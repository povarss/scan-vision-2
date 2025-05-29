<?php

namespace App\Services\Patterns;

interface PatternMakerInterface
{
    public function generate(): array;

    public function calcTotals(): array;

    public function getCorrectTotals(): array;
}
