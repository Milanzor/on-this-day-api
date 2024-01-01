<?php

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use App\Repository\EventRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;

uses(RefreshDatabase::class);

it('inserts an event', function () {

    /** @var EventRepository $eventRepository */
    $eventRepository = App::make(EventRepository::class);

    $event = new EventDataObject(
        description: 'Test description',
        month: 1,
        day: 1,
        category: Category::Births,
        language: Language::English,
        source: Source::Seed,
    );

    $result = $eventRepository->insertEvent($event);

    expect($result)->toBeTrue();


});

it('It does not insert duplicate events', function () {

    /** @var EventRepository $eventRepository */
    $eventRepository = App::make(EventRepository::class);

    $event = new EventDataObject(
        description: 'Test description',
        month: 1,
        day: 1,
        category: Category::Births,
        language: Language::English,
        source: Source::Seed,
    );

    $eventRepository->insertEvent($event);
    $result = $eventRepository->insertEvent($event);

    expect($result)->toBeNull();

});
