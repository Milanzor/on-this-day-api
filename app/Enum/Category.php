<?php

namespace App\Enum;

use App\EventSource\Wikimedia\Enum\WikimediaCategory;

enum Category: string
{

    case Births = 'births';
    case Deaths = 'deaths';
    case Holidays = 'holidays';
    case Regular = 'regular';


    public static function fromWikimediaCategory(string|WikimediaCategory $category): self
    {

        $WikimediaCategory = $category instanceof WikimediaCategory ? $category : WikimediaCategory::tryFrom($category);

        return match ($WikimediaCategory) {
            WikimediaCategory::Births => self::Births,
            WikimediaCategory::Deaths => self::Deaths,
            WikimediaCategory::Holidays => self::Holidays,
            default => self::Regular,
        };
    }
}
