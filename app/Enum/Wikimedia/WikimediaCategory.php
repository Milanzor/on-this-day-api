<?php

namespace App\Enum\Wikimedia;

enum WikimediaCategory: string
{
    case Selected = 'selected';
    case Births = 'births';
    case Deaths = 'deaths';
    case Holidays = 'holidays';
    case Events = 'events';
}
