<?php


use App\EventSource\Wikimedia\WikimediaEventSource;

it('should set an access token header', function () {

    $WikimediaEventSource = new WikimediaEventSource('example');

    $WikimediaEventSource->willFake();

    expect($WikimediaEventSource->client()->getOptions()['headers']['Authorization'])
        ->toContain('example');

});
