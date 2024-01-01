<?php


namespace App\Enum;

use App\Enum\Wikimedia\WikimediaLanguage;
use Exception;

enum Language: string
{
    case English = 'en';
    case Nederlands = 'nl';

    public static function fromWikimediaLanguage(string|WikimediaLanguage $language): self
    {
        $WikimediaLanguage = $language instanceof WikimediaLanguage ? $language : WikimediaLanguage::tryFrom($language);

        return match ($WikimediaLanguage) {
            WikimediaLanguage::English => self::English,
            WikimediaLanguage::Nederlands => self::Nederlands,
            default => throw new Exception('Language not found'),
        };
    }
}
