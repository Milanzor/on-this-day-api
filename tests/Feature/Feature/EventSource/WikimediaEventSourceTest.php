<?php


use App\EventSource\Wikimedia\WikimediaEventSource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

it('should set an access token header', function () {

    $WikimediaEventSource = new WikimediaEventSource('example');

    expect($WikimediaEventSource->client()->getOptions()['headers']['Authorization'])
        ->toContain('example');

});

it('collects events', function () {

    $WikimediaEventSource = new WikimediaEventSource('example');

    $WikimediaEventSource
        ->fake()
        ->fetch();

    $collectedEvents = $WikimediaEventSource->collectEventDataObjects();

    expect($collectedEvents)
        ->toBeInstanceOf(Collection::class)
        ->and($collectedEvents->isEmpty())
        ->toBeFalse();

});
