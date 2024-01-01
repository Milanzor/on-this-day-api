<?php

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;

it('generates a correct hash', function () {


    $event = new EventDataObject(
        description: 'Test',
        month: 1,
        day: 1,
        category: Category::Births,
        language: Language::English,
        source: Source::Test,
    );

    $hash = $event->hash();

    expect($hash)->toBe('9882f82142e584ba9abed0ad37eed75185d48cec848286c7cb0df27ecfde2df8');
});
