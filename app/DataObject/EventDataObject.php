<?php

namespace App\DataObject;

use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use App\Enum\Wikimedia\WikimediaLanguage;

class EventDataObject
{


    public function __construct(
        public readonly string $description,
        public readonly int $month,
        public readonly int $day,
        public readonly Category $category,
        public readonly Language $language,
        public readonly Source $source,
        public readonly ?string $url = null,
        public readonly ?int $year = null,
    ) {

    }

    public static function fromWikimedia(
        array $event,
        int $month,
        int $day,

        WikimediaLanguage $wikimediaLanguageEnum
    ): self {
        return new self(
            description: $event['text'],
            month: $month,
            day: $day,
            category: Category::tryFrom($event['type']),
            language: Language::tryFrom($wikimediaLanguageEnum->value),
            year: $event['year'],
        );
    }

    public function hash(): string
    {
        return hash(
            'sha256',
            $this->description.$this->month.$this->day.$this->category->value.$this->language->value.$this->year
        );
    }

}
