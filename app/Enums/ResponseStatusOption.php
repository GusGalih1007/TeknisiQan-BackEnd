<?php

namespace App\Enums;

enum ResponseStatusOption: string
{
    case Processed = 'processed';
    case Delayed = 'delayed';
    case Solved = 'solved';
}
