<?php

namespace App\Dto;

class TestAccessDto
{
    public function __construct(
        public $activatedAt,

        public $endDate,
        public $days,
        public $minutes,

        public string $type,
    ) {}
}
