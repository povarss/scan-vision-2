<?php

use App\Models\PatientExamOptions;

return [
    'directions' => [
        [
           'title' => "Неглект",
            'value' => PatientExamOptions::DIRECTION_RIGHT_LEFT,
            'icon' => ['icon' => "tabler-arrow-left", 'size' => "40"],
        ],
        [
           'title' => "",
            'value' => PatientExamOptions::DIRECTION_LEFT_RIGHT,
            'icon' => ['icon' => "tabler-arrow-right", 'size' => "40"],

        ],
        [
           'title' => "",
            'value' => PatientExamOptions::DIRECTION_TOP_BOTTOM,
            'icon' => ['icon' => "tabler-arrow-down", 'size' => "40"],

        ],
        [
           'title' => "",
            'value' => PatientExamOptions::DIRECTION_BOTTOM_TOP,
            'icon' => ['icon' => "tabler-arrow-up", 'size' => "40"],

        ],
    ],
];
