<?php


use App\EventSource\Wikimedia\WikimediaEventSource;
use Illuminate\Support\Facades\Http;

it('should set an access token header', function () {

    $WikimediaEventSource = new WikimediaEventSource('example');

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

    expect($WikimediaEventSource->client()->getOptions()['headers']['Authorization'])->toContain('example');

});
