<?php

namespace App\EventSource\Wikimedia\Enum;

/**
 * @link https://api.wikimedia.org/wiki/Feed_API/Language_support#On_this_day_in_history
 */
enum WikimediaLanguage: string
{
    case English = 'en';
    case German = 'de';
    case French = 'fr';
    case Swedish = 'sv';
    case Portuguese = 'pt';
    case Russian = 'ru';
    case Spanish = 'es';
    case Arabic = 'ar';
    case Bosnian = 'bs';
}
