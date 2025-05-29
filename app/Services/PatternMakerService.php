<?php

namespace App\Services;

use App\Models\PatientExam;
use App\Services\Patterns\PatternMakerInterface;
use App\Services\Patterns\SvgPatternMaker;
use App\Services\Patterns\NumberPatternMaker;
use InvalidArgumentException;

class PatternMakerService
{
    private array $pattern = [];
    private array $additionalItems = [];

    private const PATTERN_MAKERS = [
        1 => SvgPatternMaker::class,
        2 => SvgPatternMaker::class,
        3 => SvgPatternMaker::class,
        4 => NumberPatternMaker::class,
        5 => SvgPatternMaker::class,
        6 => SvgPatternMaker::class,
    ];

    public function __construct(
        private readonly PatientExam $patientExam,
        private readonly int $width,
        private readonly int $height
    ) {}

    public function makePattern(): array
    {
        $patternMaker = $this->resolvePatternMaker();

        $result = $patternMaker->generate();
        $this->pattern = $result['pattern'] ?? [];
        $this->additionalItems = $result['additional_items'] ?? [];

        return [
            'pattern' => $this->pattern,
            'additional_items' => $this->additionalItems,
        ];
    }

    private function resolvePatternMaker(): PatternMakerInterface
    {
        $makerClass = self::PATTERN_MAKERS[$this->patientExam->exam_id] ?? null;

        if (!$makerClass) {
            throw new InvalidArgumentException(
                "Unsupported exam type: {$this->patientExam->exam_id}"
            );
        }

        return new $makerClass($this->patientExam, $this->width, $this->height);
    }

    public function getPattern(): array
    {
        return $this->pattern;
    }

    public function getAdditionalItems(): array
    {
        return $this->additionalItems;
    }

    public function calcTotals(): array
    {
        $patternMaker = $this->resolvePatternMaker();
        return $patternMaker->calcTotals();
    }

    public function getCorrectTotals(): array
    {
        $patternMaker = $this->resolvePatternMaker();
        return $patternMaker->getCorrectTotals();
    }
}
