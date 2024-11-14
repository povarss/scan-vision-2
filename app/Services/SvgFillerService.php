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
    public $offsetYRange = 5;

    public $svgWidth = 25;
    public $svgHeight = 25;

    public $correctSvgInPie = 1;

    public $sections = [];

    public $isDual = false;
    public $patientExam;
    public function __construct(PatientExam $patientExam)
    {
        $this->patientExam = $patientExam;
        $this->setParams();
    }
    public function checkArray()
    {
        $data = json_decode(file_get_contents(public_path('test.json')), true);

        $this->items = $data;
        foreach ($data as $n => $row) {
            $this->curY = $n;
            foreach ($row as $m => $svg) {
                $this->curX = $m;
                $checkSvgs = $this->getCheckSvgs();
                $isOverlap = $this->checkSvgOverlap($svg, $checkSvgs);
                if ($this->curY == 1 && $this->curX == 1) {
                    dd($isOverlap);
                }
            }
        }
    }

    public function makePrint()
    {
        while ($this->y < $this->maxHeight) {
            $this->fillInit();

            $this->generateSvgObject();
        }
        $this->setCorrect();

        return response()->json($this->items);
    }

    public function setCorrect()
    {
        foreach ($this->sections as $section) {
            $setedSvg = 0;
            $correctSvgs = $this->isDual ? [1] : [1, 2];
            foreach ($correctSvgs as $correctSvg) {
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

    public function setDual()
    {
        $this->isDual = ($this->patientExam->mode == 2);
    }

    function cmToPx($cm) {
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

    public function setParams(){
        $this->setDual();
        $this->setContainerWidth();
        $this->setOffset();
    }

    public function setOffset(){
        $this->offsetXRange = intval($this->calcWidth() / 2);
        $this->offsetYRange = intval($this->calcHeight() / 2);
    }

    public function calcWidth()
    {
        return $this->svgWidth * $this->patientExam->svg_size / 100;
    }

    public function calcHeight()
    {
        return $this->svgHeight * $this->patientExam->svg_size / 100;
    }
    public function getSvgTypes()
    {
        $svgTypes = [];
        for ($i = 1; $i < 12; $i++) {
            if (in_array($i, [1, 2])) {
                continue;
            }
            $svgTypes[] = [
                'type' => $i,
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
        $checkSvgs = $this->getCheckSvgs();

        do {
            $positions = $this->getPosition($this->x, $this->y);
            $svg['x'] = $positions[0];
            $svg['y'] = $positions[1];
            $isOverlap = $this->checkSvgOverlap($svg, $checkSvgs);
        } while ($isOverlap);

        $this->pushSvg($svg);
    }

    public function checkSvgOverlap($curSvg, $checkSvgs)
    {
        $isOverlap = true;
        if (empty($checkSvgs)) {
            $isOverlap = false;
        }
        foreach ($checkSvgs as $key => $checkSvg) {
            $isOverlap = $this->checkIsOverlap($curSvg, $checkSvg);
            if ($isOverlap) {
                break;
            }
        }
        return $isOverlap;
    }

    public function getCheckSvgs()
    {
        $checkSvgs = [];
        $previousSvg = $this->getPreviousSvg();
        if (!empty($previousSvg)) {
            $checkSvgs[] = $previousSvg;
        }
        for ($i = 0; $i < 3; $i++) {
            $checkSvg = $this->getItemByPosition($this->curX + $i - 1, $this->curY - 1);
            if ($this->curX == 0 && $this->curY == 3) {
                // var_dump($this->curY - 1, $this->curX + $i - 1, $checkSvg);
            }
            if (!empty($checkSvg)) {
                $checkSvgs[] = $checkSvg;
            }
        }
        return $checkSvgs;
    }

    public function pushSvg($svg)
    {
        $this->items[$this->curY][$this->curX] = $svg;
        $this->addSection();
        $this->x = $svg['x'] + $svg['width'];

        if ($this->x >= $this->maxWidth) {
            $this->y = $this->y + $this->ySpace;
            $this->x = 0;
            $this->curY++;
            $this->curX = 0;
        } else {
            $this->curX++;
        }
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
        $this->items[$this->curY][$this->curX]['section'] = $sectionNum;
        if ($sectionNum < 9) {
            $this->sections[intval($angle / 45) + 1][] = ['x' => $this->curX, 'y' => $this->curY];
        }
        return $angle_degrees;
    }

    public function checkIsOverlap($item1, $item2)
    {
        if (empty($item1) || empty($item2)) {
            return false;
        }
        $isOverlap = $this->isOverlapping($item1, $item2);
        return $isOverlap;
    }

    function isOverlapping(array $rect1, array $rect2): bool
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

        // Check for overlap
        return !(
            $r1Right < $r2Left ||
            $r1Left > $r2Right ||
            $r1Bottom < $r2Top ||
            $r1Top > $r2Bottom
        );
    }

    public function getPosition($startX, $startY)
    {
        $x = $startX + $this->xSpace + rand(0, $this->offsetXRange);
        $y = $startY + rand(0, 2 * $this->offsetYRange) - $this->offsetYRange;
        if ($y < 0) {
            $y = 0;
        }

        return [$x, $y];
    }
}
