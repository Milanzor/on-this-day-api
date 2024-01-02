<?php


use App\EventSource\Wikimedia\WikimediaEventSource;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('should set an access token header', function () {

    $WikimediaEventSource = new WikimediaEventSource('example');

    $WikimediaEventSource->willFake();

    expect($WikimediaEventSource->client()->getOptions()['headers']['Authorization'])
        ->toContain('example');

});
