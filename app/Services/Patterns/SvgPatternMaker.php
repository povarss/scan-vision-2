<?php

namespace App\Services\Patterns;

use App\Models\PatientExam;
use App\Services\SvgFillerService;
use Illuminate\Support\Arr;

class SvgPatternMaker implements PatternMakerInterface
{
    public function __construct(
        private readonly PatientExam $patientExam,
        private readonly int $width,
        private readonly int $height
    ) {}

    public function generate(): array
    {
        $filler = new SvgFillerService($this->patientExam, $this->width, $this->height);
        $pattern = $filler->makePrint();

        $additionalItems = [];
        if ($this->patientExam->type === PatientExam::TYPE_WITH_DOTS) {
            $additionalItems['dots'] = $filler->makeDots();
        }


        return [
            'pattern' => $pattern,
            'additional_items' => $additionalItems,
        ];
    }

    public function calcTotals(): array
    {
        $correctTotals = ['total' => 0, 'left' => 0, 'right' => 0];
        foreach ($this->patientExam->pattern as $row) {
            foreach ($row as $item) {
                if ($item['isCorrect']) {
                    $correctTotals['total'] = $correctTotals['total'] + 1;
                    if (in_array($item['section'], [1, 2, 7, 8])) {
                        $correctTotals['right'] = $correctTotals['right'] + 1;
                    } else {
                        $correctTotals['left'] = $correctTotals['left'] + 1;
                    }
                }
            }
        }
        return $correctTotals;
    }

    public function getCorrectTotals(): array
    {
        $correctCount = ['left' => 0, 'right' => 0, 'total' => 0, 'selected' => 0];
        $incorrectCount = ['left' => 0, 'right' => 0, 'total' => 0, 'left_double' => 0, 'right_double' => 0, 'double_total' => 0];

        $doubleClicks = collect($this->patientExam->double_clicks)->keyBy(function (array $item, int $key) {
            return $item[0] . '_' . $item[1];
        });

        foreach ($this->patientExam->result as $key => $position) {
            $item = $this->patientExam->pattern[$position[0]][$position[1]];
            if ($item['isCorrect']) {
                $correctCount['selected'] = $correctCount['selected'] + 1;
                if (in_array($item['section'], [1, 2, 7, 8])) {
                    $correctCount['right'] = $correctCount['right'] + 1;
                } else {
                    $correctCount['left'] = $correctCount['left'] + 1;
                }
            } else {
                $incorrectCount['total'] = $incorrectCount['total'] + 1;

                if (in_array($item['section'], [1, 2, 7, 8])) {
                    $incorrectCount['right'] = $incorrectCount['right'] + 1;
                } else {
                    $incorrectCount['left'] = $incorrectCount['left'] + 1;
                }
                if ($doubleClicks->has($position[0] . '_' . $position[1])) {
                    $clicks = Arr::get($doubleClicks->get($position[0] . '_' . $position[1]), 2);
                    $incorrectCount['double_total'] += $clicks;
                    if (in_array($item['section'], [1, 2, 7, 8])) {
                        $incorrectCount['right_double'] +=  $clicks;
                    } else {
                        $incorrectCount['left_double'] +=  $clicks;
                    }
                }
            }
        }

        return [$correctCount, $incorrectCount];
    }
}
