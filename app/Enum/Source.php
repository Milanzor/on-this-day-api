<?php

namespace App\Enum;

enum Source: string
{
    case Wikimedia = 'wikimedia';
    case Seed = 'seed';
    case Test = 'test';
}

