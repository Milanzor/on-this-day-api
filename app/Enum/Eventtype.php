<?php

namespace App\Enum;

enum Eventtype: string
{

    case Births = 'births';
    case Deaths = 'deaths';
    case Events = 'events';
    case Holidays = 'holidays';
    case Selected = 'selected';
    case Other = 'other';
}
