<?php

namespace App\Enum;

Class TaskEnum
{
    const OPEN = 'OPEN';
    const PENDING = 'PENDING';
    const PROGRESS = 'PROGRESS';
    const REVIEW = 'REVIEW';
    const ACCEPTED = 'ACCEPTED';
    const REJECTED = 'REJECTED';

    const STATUS = [
        self::OPEN,
        self::PENDING,
        self::PROGRESS,
        self::REVIEW,
        self::ACCEPTED,
        self::REJECTED,
    ];
}
