<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans
        }
    </style>
</head>

<body>
    <div>
        ПІБ: {{ $patientExam->patient->full_name }}
    </div>
    <div>
        Час скринінгу {{ $totals['testMinute'].'.'.$totals['testSecond'] }}хв з {{ $totals['totalMinute'] }}
    </div>
    <div>
        Кількість правильних стимулів: {{ $totals['correctCount']['selected'] }} з
        {{ $totals['correctCount']['total'] }}
    </div>
    <div>
        Кількість правильних цілей з правого боку:{{ $totals['correctCount']['right'] }}
    </div>
    <div>
        Кількість правильних цілей з лівого боку:{{ $totals['correctCount']['left'] }}
    </div>
    <div>
        Кількість неправильних стимулів:{{ $totals['incorrectCount']['total'] }}
    </div>
    <div>
        Кількість файльш цілей з правого боку:{{ $totals['incorrectCount']['right'] }}
    </div>
    <div>
        Кількість файльш цілей з лівого боку:{{ $totals['incorrectCount']['total'] }}
    </div>
    <div style="position: relative; width:400px; height:400px;">
        <div style="position: absolute; top:20; left:-150; transform: scale(0.38, 0.38);">
            @php
                $line = '';
                $points = [];
                $maxX = 0;
                $maxY = 0;
                foreach ($patientExam->pattern as $row) {
                    foreach ($row as $cell) {
                        if ($cell['x'] > $maxX) {
                            $maxX = $cell['x'] + $cell['width'] / 2;
                        }
                        if ($cell['y'] > $maxY) {
                            $maxY = $cell['x'] + $cell['height'] / 2;
                        }
                    }
                }
                // dd($maxX);
                foreach ($patientExam->result as $checked) {
                    $item = $patientExam->pattern[$checked[0]][$checked[1]];
                    $points[] = ['x' => $item['x'] + $item['width'] / 2, 'y' => $item['y'] + $item['height'] / 2];
                    $line .= $item['x'] + $item['width'] / 2 . ',' . ($item['y'] + $item['height'] / 2) . ' ';
                }
                $svg =
                    '<svg width="' .
                    ceil($maxX) .
                    '" height="' .
                    ceil($maxY) .
                    '" style="border: none">
                <polyline points="' .
                    $line .
                    '" stroke="#b7b7b7" fill="none" stroke-width="2" />';

                foreach ($points as $key => $point) {
                    $color = '#b7b7b7';
                    if($key == 0){
                        $color = 'red';
                    }
                    if($key == count($points) - 1){
                        $color = '#82ff00';
                    }
                    $svg .= '<circle cx="' . $point['x'] . '" cy="' . $point['y'] . '" r="10" fill="' . $color . '" />';
                }
                $svg .= '</svg>';
                // dd($svg);
            @endphp
            <div style="z-index: 90; position: absolute; left:0px; top:0">
                <img src="data:image/svg+xml;base64,{{ base64_encode($svg) }}'" width="{{ $maxX }}"
                    height="{{ $maxY }}" />';
            </div>
            @foreach ($patientExam->pattern as $rowKey => $row)
                @foreach ($row as $colKey => $imgItem)
                    @php
                        $isSelected = false;
                        foreach ($patientExam->result as $checked) {
                            if ($checked[0] == $rowKey && $checked[1] == $colKey) {
                                $isSelected = true;
                                break;
                            }
                        }

                        $color = '';

                        if ($isSelected) {
                            if ($imgItem['isCorrect'] == 1) {
                                $color = '#00ff72';
                            } else {
                                $color = '#ff6f6f';
                            }
                        } else {
                            if ($imgItem['isCorrect'] == 1) {
                                $color = '#fbf525';
                            }
                        }
                    @endphp
                    <img src="{{ public_path('/images/vision/' . $imgItem['type'] . '.svg') }}"
                        width="{{ $imgItem['width'] }}" height="{{ $imgItem['width'] }}"
                        style="position: absolute;padding: 2px;
                    top: {{ $imgItem['y'] . 'px' }}; left: {{ $imgItem['x'] . 'px' }}; background-color: {{ $color }}" />
                @endforeach
            @endforeach
        </div>
    </div>
</body>

</html>
