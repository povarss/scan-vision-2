<?php

namespace App\Services;

use App\Models\PatientExam;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class NumberRandomPlacerService
{
    public int $startNumber = 1;
    public int $colNumber = 3;
    public int $rowNumber = 3;

    public array $pattern = [];
    public function __construct(
        protected PatientExam $patientExam,
        protected int $width,
        protected int $height
    ) {
        $this->setParams();
    }

    public function setParams()
    {
        $config = config('exam.' . $this->patientExam->exam_id);
        $config = $config['levels'][$this->patientExam->level];
        $this->colNumber = $config['x'];
        $this->rowNumber = $config['y'];
    }

    public function makePattern(): array
    {
        $numbers = range($this->startNumber, $this->colNumber * $this->rowNumber);
        shuffle($numbers);
        $result = [];
        for ($i = 1; $i <= $this->rowNumber; $i++) {
            $result[$i - 1] = array_slice(
                $numbers,
                ($i - 1) * $this->colNumber,
                $this->colNumber
            );
        }
        $this->pattern = $result;
        return $this->pattern;
    }

    public function makeDots()
    {
        $dots = [];
        $slices_x = 10; //(int)floor($this->maxHeight / $slice_width);
        $slices_y = 10; //(int)floor($height / $slice_height);
        $slice_width = (int)floor($this->width / $slices_x);
        $slice_height = (int)floor($this->height / $slices_y);
        $num_dots = $this->patientExam->custom_settings['dot_count'];
        $radius = 11;
        // Calculate approximate dots per slice
        $dots_per_slice = (int)ceil($num_dots / ($slices_x * $slices_y));

        for ($i = 0; $i < $slices_x; $i++) {
            $slice_x_start = $i * $slice_width;
            $slice_x_end = $slice_x_start + $slice_width;
            for ($j = 0; $j < $slices_y; $j++) {
                $slice_y_start = $j * $slice_height;
                $slice_y_end = $slice_y_start + $slice_height;
                // echo $slice_height.' '.$slice_width.' '. $slice_y_start.' '.$slice_x_start.' end ';

                $maxDots = ($i * $slices_x * $dots_per_slice)  + ($j + 1) * $dots_per_slice;
                // echo $maxDots.' ';
                while (count($dots) < $maxDots) {
                    $x = rand($slice_x_start, $slice_x_end - 1);
                    $y = rand($slice_y_start, $slice_y_end - 1);

                    // Check for overlap (adjust distance as needed)
                    $overlap = false;
                    foreach ($dots as $dot) {
                        if (abs($x - $dot[0]) < $radius && abs($y - $dot[1]) < $radius) {
                            $overlap = true;
                            break;
                        }
                    }

                    if (!$overlap) {
                        $dots[] = [$x, $y, $slice_x_start, $slice_x_end, $slice_y_start, $slice_y_end, $i, $j];
                    }
                }
            }
        }

        return $dots;
    }
}
