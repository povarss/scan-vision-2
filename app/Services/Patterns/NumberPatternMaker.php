<?php

namespace App\Services\Patterns;

use App\Models\PatientExam;
use App\Services\NumberRandomPlacerService;
use Illuminate\Support\Arr;

class NumberPatternMaker implements PatternMakerInterface
{
    public function __construct(
        private readonly PatientExam $patientExam,
        private readonly int $width,
        private readonly int $height
    ) {}

    public function generate(): array
    {
        $placer = new NumberRandomPlacerService(
            $this->patientExam,
            $this->width,
            $this->height
        );

        $additionalItems = [];
        if ($this->patientExam->type === PatientExam::TYPE_WITH_DOTS) {
            $additionalItems['dots'] = $placer->makeDots();
        }


        return [
            'pattern' => $placer->makePattern(),
            'additional_items' => $additionalItems,
        ];
    }

    public function calcTotals(): array
    {
        $levelInfo = config('exam.4.levels.' . $this->patientExam->level);
        $correctTotals = [
            'total' => $levelInfo['x'] * $levelInfo['y'],
            'left' => 0,
            'right' => 0,
        ];
        return $correctTotals;
    }

    public function getCorrectTotals(): array
    {
        $correctCount = ['left' => 0, 'right' => 0, 'total' => 0, 'selected' => 0];
        $incorrectCount = ['left' => 0, 'right' => 0, 'total' => 0, 'left_double' => 0, 'right_double' => 0, 'double_total' => 0];

        $doubleClicks = collect($this->patientExam->double_clicks)->keyBy(function (array $item, int $key) {
            return $item[0] . '_' . $item[1];
        });

        $startNumber = 1;

        foreach ($this->patientExam->result as $key => $position) {
            $item = $this->patientExam->pattern[$position[0]][$position[1]];

            // echo $startNumber . ' - ' . $item .' '.$correctCount['selected']. '<br>';
            if ($startNumber == $item) {
                $startNumber++;
                $correctCount['selected'] = $correctCount['selected'] + 1;
            } else {
                $incorrectCount['total'] = $incorrectCount['total'] + 1;
            }
        }
        $incorrectCount['double_total'] = $doubleClicks->count();
        return [$correctCount, $incorrectCount];
    }
}
