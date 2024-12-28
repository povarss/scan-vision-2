<?php

namespace App\Services;

use App\Models\PatientExam;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SvgFillerService
{
    public $xSpace = 2;
    public $ySpace = 50;
    public $maxWidth = 400;
    public $maxHeight = 400;
    public $items = [];
    public $x = 0;
    public $y = 0;
    public $curX = 0;
    public $curY = 0;

    public $offsetXRange = 25;
    public $offsetYRange = 25;

    public $svgWidth = 50;
    public $svgHeight = 50;

    public $correctSvgInPie = 1;

    public $sections = [];

    public $overlapCounter = 0;
    public $patientExam;

    public $map = [];
    public function __construct(PatientExam $patientExam, $width, $height)
    {
        $this->patientExam = $patientExam;
        $this->maxWidth = $width;
        $this->maxHeight = $height;
        $this->setParams();
    }

    public function isCheckNum($svg)
    {
        return ($svg['y'] == 170 && $svg['x'] == 491 && $svg['type'] == 9) || ($svg['y'] == 160 && $svg['x'] == 379 && $svg['type'] == 9);
    }

    public function checkPattern()
    {
        $data = $this->patientExam->pattern;
        $this->items = $data;
        foreach ($data as $n => $row) {
            $this->curY = $n;
            foreach ($row as $m => $svg) {
                $this->curX = $m;
                $checkSvgs = $this->getCheckSvgs($svg);
                if ($this->isCheckNum($svg)) {
                    $svg['x'] = $svg['oldX'];
                    $svg['y'] = $svg['oldY'];
                }
                list($isOverlap, $newPos) = $this->checkSvgOverlap($svg, $checkSvgs);
                if ($this->isCheckNum($svg)) {
                    dd($isOverlap, $newPos, $this->map, $checkSvgs, $svg, $n, $m);
                }
                $this->setOnMap($svg);
            }
        }
    }

    public function makePrint()
    {
        $num = 0;
        while ($this->y < $this->maxHeight - $this->calcWidth()) {
            $this->fillInit();

            $this->generateSvgObject();
            $num++;
        }
        $this->setCorrect();

        return $this->items;
    }

    public function getLevelCorrectCount()
    {
        $config = $this->getExamConfig();
        return Arr::get($config, 'levels.' . $this->patientExam->level . '.count', $config['default_max_count']);
    }

    public function setCorrect()
    {
        $config = $this->getExamConfig();
        $correctSvgs = $config['svgs'][$this->patientExam->mode];
        $maxCount = $this->getLevelCorrectCount();

        $insertedCount = 0;
        $needFill = 0;
        // dd($maxCount);
        $correctSvgInPie = intval($maxCount / count($this->sections));
        $canAddons = $maxCount - $correctSvgInPie * count($this->sections);

        foreach ($this->sections as $sectionIndex => $sectionItems) {
            $oldAdditions = $needFill - $insertedCount;
            $maxSvgsPerSection = $correctSvgInPie + ($sectionIndex <= $canAddons ? 1 : 0);
            $needFill += $maxSvgsPerSection;
            $maxSvgsPerSection += $oldAdditions;

            shuffle($sectionItems);
            foreach ($correctSvgs as $correctSvg) {
                for ($i = 0; $i < $maxSvgsPerSection; $i++) {
                    if (empty($sectionItems)) {
                        break; // No more positions available in this section
                    }
                    $position = array_pop($sectionItems);
                    if (!$this->items[$position['y']][$position['x']]['isCorrect']) {
                        $this->items[$position['y']][$position['x']]['type'] = $correctSvg;
                        $this->items[$position['y']][$position['x']]['isCorrect'] = 1;
                        $insertedCount++;
                    }
                }
            }
        }
    }

    function cmToPx($cm)
    {
        // Assuming 96 DPI
        $ppi = 96;
        $inches = $cm / 2.54; // Convert cm to inches
        $pixels = $inches * $ppi;
        return round($pixels);
    }

    public function setParams()
    {
        $this->setOffset();
    }

    public function setOffset()
    {
        $this->offsetXRange = intval($this->calcWidth());
        $this->offsetYRange = intval($this->calcHeight());
    }

    public function getLevelSize()
    {
        $config = $this->getExamConfig();
        return Arr::get($config, 'levels.' . $this->patientExam->level . '.size', $config['default_size']);
    }
    public function calcWidth()
    {

        return ceil($this->getLevelSize() * $this->patientExam->svg_size / 100);
    }

    public function calcHeight()
    {
        return ceil($this->getLevelSize() * $this->patientExam->svg_size / 100);
    }

    public function getExamConfig()
    {
        $configs = config('exam');
        return $configs[$this->patientExam->exam_id];
    }
    public function getSvgTypes()
    {
        $config = $this->getExamConfig();
        $correctSvgs = $config['svgs'][$this->patientExam->mode];
        $svgTypes = [];
        for ($i = 1; $i <= count($config['allSvgs']); $i++) {
            $curSvgName = $config['allSvgs'][$i - 1];
            if (in_array($curSvgName, $correctSvgs)) {
                continue;
            }
            $svgTypes[] = [
                'type' => $curSvgName,
                'width' => $this->calcWidth(),
                'height' => $this->calcHeight(),
                'isCorrect' => 0,
            ];
        }
        return $svgTypes;
    }
    public function fillInit()
    {
        if (empty($this->items[$this->curY])) {
            $this->items[$this->curY] = [];
        }
    }

    public function getPreviousSvg()
    {
        if (empty($this->items[$this->curY][$this->curX - 1])) {
            return null;
        }
        return $this->items[$this->curY][$this->curX - 1];
    }

    public function getItemByPosition($x, $y)
    {
        if (empty($this->items[$y]) || empty($this->items[$y][$x])) {
            return null;
        }
        return $this->items[$y][$x];
    }

    public function getPreviousSvgType()
    {
        if (empty($this->getPreviousSvg())) {
            return -1;
        }
        return $this->getPreviousSvg()['type'];
    }

    function getNewSvgRandomNum()
    {
        $previousType = $this->getPreviousSvgType();
        $types = $this->getSvgTypes();

        do {
            $randomNumber = rand(0, count($types) - 1);
        } while ($this->getSvgTypes()[$randomNumber]['type'] == $previousType);

        return $randomNumber;
    }

    public function makeNewSvg()
    {
        return $this->getSvgTypes()[$this->getNewSvgRandomNum()];
    }

    public function generateSvgObject()
    {
        $svg = $this->makeNewSvg();
        $svg['canAdd'] = true;

        do {
            $positions = $this->getPosition($this->x, $this->y);
            $svg['x'] = $positions[0];
            $svg['y'] = $positions[1];
            // $svg['isOverlaped'] = 0;
            $checkSvgs = $this->getCheckSvgs($svg);
            list($isOverlap, $newPos) = $this->checkSvgOverlap($svg, $checkSvgs);
            if ($isOverlap) {
                // $svg['isOverlaped'] = 1;
                // $svg['oldX'] = $svg['x'];
                // $svg['oldY'] = $svg['y'];

                $svg['x'] = $newPos['x'];
                $svg['y'] = $newPos['y'];
                $svg['canAdd'] = $this->isSamePos($svg, $checkSvgs);
            }
            $isOverlap = false;
            $this->overlapCounter++;
        } while ($isOverlap);
        $this->pushSvg($svg);
    }

    public function isSamePos($svg, $checkSvgs)
    {
        $hasSamePos = false;
        foreach ($checkSvgs as $checkSvg) {
            if ($checkSvg['x'] == $svg['x'] && $checkSvg['y'] == $svg['x']) {
                $hasSamePos = true;
                break;
            }
        }
        return $hasSamePos;
    }

    public function checkSvgOverlap($curSvg, $checkSvgs)
    {
        $isOverlap = true;
        if (empty($checkSvgs)) {
            return [false, null];
        }
        $isOverlap = false;
        foreach ($checkSvgs as $key => $checkSvg) {
            list($isOverlaped, $newPos) = $this->checkIsOverlap($curSvg, $checkSvg);
            if ($isOverlaped) {
                $curSvg['x'] = $newPos[0];
                $curSvg['y'] = $newPos[1];
                $isOverlap = true;
            }
        }
        return [$isOverlap, $curSvg];
    }

    public function getCheckSvgs($svg)
    {
        $checkSvgs = [];
        $previousSvg = $this->getPreviousSvg();
        if (!empty($previousSvg)) {
            $checkSvgs[] = $previousSvg;
        }

        $baseXPos = $this->getXCell($svg);
        $yPos = $this->getYCell($svg);
        // dd($yPos);
        for ($i = 1; $i < 4; $i++) {
            $xPos = $baseXPos + $i * 100 - 200;

            if (!empty($this->map[$xPos])) {
                $curYpos = $yPos;
                while ($yPos - $curYpos < 300 && $curYpos >= 0) {
                    if (!empty($this->map[$xPos][$curYpos])) {
                        foreach ($this->map[$xPos][$curYpos] as  $mapSvg) {
                            $checkSvgs[] = $mapSvg;
                        }
                    }
                    $curYpos -= 100;
                }
            }
        }
        return $checkSvgs;
    }

    public function pushSvg($svg)
    {
        $isInArea = $svg['x'] < $this->maxWidth - $this->calcWidth() && $svg['y'] < $this->maxWidth - $this->calcHeight();
        if ($svg['canAdd'] && $isInArea) {
            $this->items[$this->curY][$this->curX] = $svg;
            $this->addSection();
            $this->setOnMap($svg);
        }
        $this->x = $svg['x'] + $svg['width'];

        if ($this->x >= $this->maxWidth - $this->calcWidth()) {
            $this->y = $this->y + $this->ySpace;
            $this->x = 0;
            $this->curY++;
            $this->curX = 0;
        } else {
            $this->curX++;
        }
    }

    public function getXCell($svg)
    {
        return ($svg['x'] - $svg['x'] % 100);
    }

    public function getYCell($svg)
    {
        return ($svg['y'] - $svg['y'] % 100);
    }

    public function setOnMap($svg)
    {
        $xPos = $this->getXCell($svg);
        if (empty($this->map[$xPos])) {
            $this->map[$xPos] = [];
        }
        if (empty($this->map[$xPos][$this->getYCell($svg)])) {
            $this->map[$xPos][$this->getYCell($svg)] = [];
        }
        $this->map[$xPos][$this->getYCell($svg)][] = $svg;
    }

    public function addSection()
    {
        $centerX = $this->maxWidth / 2;
        $centerY = $this->maxHeight / 2;
        $curSvg = $this->getItemByPosition($this->curX, $this->curY);
        $dx = $curSvg['x'] - $centerX;
        $dy = $curSvg['y'] - $centerY;

        $angle_radians = atan2($dy, $dx);
        $angle_degrees = rad2deg($angle_radians);
        // Adjust the angle to be in the range of 0 to 360 degrees
        if ($angle_degrees < 0) {
            $angle_degrees += 360;
        }

        $angle = 360 - intval($angle_degrees);
        $this->items[$this->curY][$this->curX]['angle'] = $angle;
        $sectionNum = intval($angle / 45) + 1;
        if ($sectionNum >= 9) {
            $sectionNum = 1;
        }
        $this->items[$this->curY][$this->curX]['section'] = $sectionNum;
        $this->sections[$sectionNum][] = ['x' => $this->curX, 'y' => $this->curY];
        return $angle_degrees;
    }

    public function checkIsOverlap($item1, $item2): array
    {
        if (empty($item1) || empty($item2)) {
            return [false, []];
        }
        $isOverlapInfo = $this->isOverlapping($item1, $item2);
        return $isOverlapInfo;
    }

    function isOverlapping(array $rect1, array $rect2): array
    {
        // Extract coordinates and dimensions from rectangles
        $x1 = $rect1['x'];
        $y1 = $rect1['y'];
        $w1 = $rect1['width'];
        $h1 = $rect1['height'];

        $x2 = $rect2['x'];
        $y2 = $rect2['y'];
        $w2 = $rect2['width'];
        $h2 = $rect2['height'];

        // Calculate boundaries
        $r1Left = $x1;
        $r1Right = $x1 + $w1;
        $r1Top = $y1;
        $r1Bottom = $y1 + $h1;

        $r2Left = $x2;
        $r2Right = $x2 + $w2;
        $r2Top = $y2;
        $r2Bottom = $y2 + $h2;

        $newPos = [$x1, $y1];

        if (
            $r1Right > $r2Left &&
            $r1Left < $r2Right &&
            $r1Bottom > $r2Top &&
            $r1Top < $r2Bottom
        ) {
            // Adjust rect1's position to avoid overlap
            if ($r1Right > $r2Left) {
                $newPos[0] = $r2Right;
            } elseif ($r1Left < $r2Left) {
                $newPos[0] = $r2Left - $w1;
            }

            if ($r1Bottom > $r2Top) {
                $newPos[1] = $r2Bottom;
            } elseif ($r1Top < $r2Top) {
                $newPos[1] = $r2Top - $h1;
            }

            return [true, $newPos];
        }

        return [false, $newPos];
    }

    public function getPosition($startX, $startY)
    {
        $x = $startX + $this->xSpace + rand(0, $this->offsetXRange);
        $y = $startY + rand(0,  $this->offsetYRange / 2);
        if ($y < 0) {
            $y = 0;
        }

        return [$x, $y];
    }

    public function makeDots()
    {
        $dots = [];
        $slices_x = 10; //(int)floor($this->maxHeight / $slice_width);
        $slices_y = 10; //(int)floor($height / $slice_height);
        $slice_width = (int)floor($this->maxWidth / $slices_x);
        $slice_height = (int)floor($this->maxHeight / $slices_y);
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
