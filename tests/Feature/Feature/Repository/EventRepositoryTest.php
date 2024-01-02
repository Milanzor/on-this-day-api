<?php

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use App\EventSource\Wikimedia\WikimediaEventSource;
use App\Models\Event;
use App\Repository\EventRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;

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
        source: Source::Test,
    );

    $result = $eventRepository->insertEvent($event);

    expect($result)->toBeTrue();


});

it('does not insert duplicate events', function () {

    /** @var EventRepository $eventRepository */
    $eventRepository = App::make(EventRepository::class);

    $event = new EventDataObject(
        description: 'Test description',
        month: 1,
        day: 1,
        category: Category::Births,
        language: Language::English,
        source: Source::Test,
    );

    $eventRepository->insertEvent($event);
    $result = $eventRepository->insertEvent($event);

    expect($result)->toBeNull();

});

it('can import from Wikimedia', function () {

    /** @var EventRepository $eventRepository */
    $eventRepository = App::make(EventRepository::class);

    Http::fake([
        'wikipedia/*' => Http::response([
            'births' => [
                [
                    'year' => '2021',
                    'text' => 'Piet',
                ],
                [
                    'year' => '2022',
                    'text' => 'Jan',
                ],
            ],
            'deaths' => [],
            'events' => [],
        ]),
    ]);

    $eventRepository->import(
        (new WikimediaEventSource(config('services.wikimedia.access_token')))
            ->setLanguage(Language::English)
            ->setMonth(1)
            ->setDay(1)
    );

    expect(Event::query()->limit(1)->count())->toBe(2);
});
