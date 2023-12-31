<?php

namespace App\DataObject;

use App\Enum\EventCategory;
use App\Enum\EventLanguage;

class EventDataObject
{


    public function __construct(
        public readonly string $description,
        public readonly int $month,
        public readonly int $day,
        public readonly EventCategory $category,
        public readonly EventLanguage $language,
        public readonly ?int $year = null,
    ) {

    }

    public function hash(): string
    {
        return hash(
            'sha256',
            $this->description.$this->month.$this->day.$this->category->value.$this->language->value.$this->year
        );
    }

}
