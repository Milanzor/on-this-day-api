<?php

namespace App\Enum;

use App\EventSource\Wikimedia\Enum\WikimediaCategory;

enum Category: string
{

    case Births = 'births';
    case Deaths = 'deaths';
    case Holidays = 'holidays';
    case Regular = 'regular';

}
