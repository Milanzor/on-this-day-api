<?php

namespace App\DataObject;

use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;

readonly class EventDataObject
{


    public function __construct(
        public string $description,
        public int $month,
        public int $day,
        public Category $category,
        public Language $language,
        public Source $source,
        public ?string $url = null,
        public ?int $year = null,
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
