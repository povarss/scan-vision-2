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
        ПИБ {{ $patientExam->patient->full_name }}
    </div>
    <div>
        Час скринінгу {{ $totals['testMinute'] }}хв з {{ $totals['totalMinute'] }}
    </div>
    <div>
        Кількість правильних стимулів:{{ $totals['correctCount']['total'] }}
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
    <div style="position: relative; width:400px; height:400px; margin-top:20px">
        @foreach ($patientExam->pattern as $rowKey => $row)
            @foreach ($row as $colKey => $imgItem)
                @php
                $isSelected = false;
                foreach ($patientExam->result as $checked) {
                    if($checked[0] == $rowKey && $checked[1] == $colKey){
                        $isSelected = true;
                        break;
                    }
                }

                $color = '';
                if($imgItem['isCorrect'] == 1){
                    $color = '#00ff72';
                }
                if($isSelected){
                    $color = '#dde6ff';
                }
                @endphp
                <img src="{{ public_path('/images/vision/' . $imgItem['type'] . '.svg') }}"
                    width="{{ $imgItem['width'] }}" height="{{ $imgItem['width'] }}"
                    style="position: absolute;top: {{ $imgItem['y'] . 'px' }}; left: {{ $imgItem['x'] . 'px' }}; background-color: {{ $color}}" />
            @endforeach
        @endforeach

    </div>
</body>

</html>
