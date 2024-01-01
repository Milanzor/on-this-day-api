<?php

namespace App\Enum;

enum Category: string
{

    case Births = 'births';
    case Deaths = 'deaths';
    case Holidays = 'holidays';
    case Selected = 'selected';
    case Other = 'other';
}
