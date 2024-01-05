<?php


use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\EventSource\Wikimedia\WikimediaEventSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

it('should set an access token header', function () {

    $WikimediaEventSource = WikimediaEventSource::fake('example')->fetch();

    expect($WikimediaEventSource->client()->getOptions()['headers']['Authorization'])
        ->toContain('example');

});

it('collects events', function () {

    $WikimediaEventSource = WikimediaEventSource::fake()->fetch();

    $collectedEvents = $WikimediaEventSource->collectEventDataObjects();

    expect($collectedEvents)
        ->toBeInstanceOf(Collection::class)
        ->and($collectedEvents->isEmpty())
        ->toBeFalse();

});


it('can transform a Wikimedia event to an EventDataObject', function () {

    $WikimediaEventSource = WikimediaEventSource::fake()->fetch();

    $event = $WikimediaEventSource->transformToEventDataObject(
        'holidays', $WikimediaEventSource->getEvents()['holidays'][0]
    );

    expect($event)
        ->toBeInstanceOf(EventDataObject::class)
        ->and($event->category)
        ->toBe(Category::Holidays);

});
