<?php

use App\Models\PatientExamOptions;

return [
    'directions' => [
        [
            'title' => "справа наліво",
            'value' => PatientExamOptions::DIRECTION_RIGHT_LEFT,
        ],
        [
            'title' => "зліва на право",
            'value' => PatientExamOptions::DIRECTION_LEFT_RIGHT,
        ],
        [
            'title' => "зверху вниз",
            'value' => PatientExamOptions::DIRECTION_TOP_BOTTOM,
        ],
        [
            'title' => "знизу вверх",
            'value' => PatientExamOptions::DIRECTION_BOTTOM_TOP,
        ],
    ],
];
