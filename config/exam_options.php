<?php

use App\Models\PatientExamOptions;

return [
    'directions' => [
        [
//            'title' => "справа наліво",
            'value' => PatientExamOptions::DIRECTION_RIGHT_LEFT,
            'icon' => ['icon' => "tabler-arrow-left", 'size' => "40"],
        ],
        [
//            'title' => "зліва на право",
            'value' => PatientExamOptions::DIRECTION_LEFT_RIGHT,
            'icon' => ['icon' => "tabler-arrow-right", 'size' => "40"],

        ],
        [
//            'title' => "зверху вниз",
            'value' => PatientExamOptions::DIRECTION_TOP_BOTTOM,
            'icon' => ['icon' => "tabler-arrow-down", 'size' => "40"],

        ],
        [
//            'title' => "знизу вверх",
            'value' => PatientExamOptions::DIRECTION_BOTTOM_TOP,
            'icon' => ['icon' => "tabler-arrow-up", 'size' => "40"],

        ],
    ],
];
