<?php

namespace App\Enum;

enum EventCategory: string
{

    case Births = 'births';
    case Deaths = 'deaths';
    case Holidays = 'holidays';
    case Selected = 'selected';
    case Other = 'other';
}
