<?php

namespace App\Services;

use App\Models\PatientExam;
use Illuminate\Http\Request;

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
            // if($num ==30){
            //     break;
            // }
        }
        $this->setCorrect();

        return $this->items;
    }

    public function setCorrect()
    {
        $config = $this->getExamConfig();
        $correctSvgs = $config['svgs'][$this->patientExam->mode];
        foreach ($this->sections as $section) {
            $setedSvg = 0;
            foreach ($correctSvgs as $correctSvg) {
                $setedSvg = 0;
                while ($setedSvg < $this->correctSvgInPie) {
                    $position = $section[rand(0, count($section) - 1)];
                    if (!$this->items[$position['y']][$position['x']]['isCorrect']) {
                        $this->items[$position['y']][$position['x']]['type'] = $correctSvg;
                        $this->items[$position['y']][$position['x']]['isCorrect'] = 1;
                        $setedSvg++;
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

    public function setContainerWidth()
    {
        $minItems = 18;
        $sizes = [
            1 => 2,
            2 => 1.5,
            3 => 1,
        ];
        $sizeInSm = $sizes[$this->patientExam->level] * $minItems;
        $this->maxWidth = $this->cmToPx($sizeInSm);
        $this->maxHeight = $this->cmToPx($sizeInSm);

        $this->maxWidth = $this->calcWidth() * $minItems;
        $this->maxHeight = $this->calcWidth() * $minItems;
    }

    public function setParams()
    {
        // $this->setContainerWidth();
        $this->setOffset();
    }

    public function setOffset()
    {
        $this->offsetXRange = intval($this->calcWidth());
        $this->offsetYRange = intval($this->calcHeight());
    }

    public function getLevels()
    {
        return [
            '1' => 76,
            '2' => 57,
            '3' => 38
        ];
    }

    public function calcWidth()
    {

        return ceil($this->getLevels()[$this->patientExam->level] * $this->patientExam->svg_size / 100);
    }

    public function calcHeight()
    {
        return ceil($this->getLevels()[$this->patientExam->level] * $this->patientExam->svg_size / 100);
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
            if (in_array($i, $correctSvgs)) {
                continue;
            }
            $svgTypes[] = [
                'type' => $config['allSvgs'][$i - 1],
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
                // if ($this->isCheckNum($curSvg)) {
                //     dd($checkSvg, $curSvg);
                // }
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
        // if ($this->curY == 1) {
        //     dd($checkSvgs, $xPos, $yPos, $this->map);
        // }
        // for ($i = -3; $i < 8; $i++) {
        //     $checkSvg = $this->getItemByPosition($this->curX + $i, $this->curY - 1);
        //     if (!empty($checkSvg)) {
        //         $checkSvgs[] = $checkSvg;
        //         if ($checkSvg['x'] > $svg['x']) {
        //             break;
        //         }
        //     }
        // }
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
        // dd($angle_degrees);
        // Adjust the angle to be in the range of 0 to 360 degrees
        if ($angle_degrees < 0) {
            $angle_degrees += 360;
        }

        $angle = 360 - intval($angle_degrees);
        $this->items[$this->curY][$this->curX]['angle'] = $angle;
        $sectionNum = intval($angle / 45) + 1;
        $this->items[$this->curY][$this->curX]['section'] = $sectionNum < 9 ? $sectionNum : 1;
        $this->sections[intval($angle / 45) + 1][] = ['x' => $this->curX, 'y' => $this->curY];
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
}
