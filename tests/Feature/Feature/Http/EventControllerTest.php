<?php

use App\EventSource\Wikimedia\WikimediaEventSource;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('fetches events', function () {

    WikimediaEventSource::fake();

    $response = $this->getJson(
        route('events.that_happened_on',
            [
                'month' => 12,
                'day' => 12,
            ]
        )
    );

    $response->assertOk();

    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'description',
                'year',
                'category',
                'happiness'
            ],
        ],
    ]);

});

it('can limit events', function () {

    WikimediaEventSource::fake();

    $response = $this->getJson(route('events.that_happened_on', [
        'month' => 12,
        'day' => 12,
        'limit' => 3,
    ]));

    $response->assertOk();

    $response->assertJsonCount(3, 'data');

});

it('can fetch events of a specific category', function () {

    WikimediaEventSource::fake();

    $response = $this->getJson(route('events.that_happened_on', [
        'month' => 12,
        'day' => 12,
        'category' => 'births',
    ]));


    $response->assertOk();

    # Extract all categories
    $categories = collect($response->json('data'))->pluck('category');

    # Assert that all categories are 'births'
    $categories->each(fn($category) => expect($category)->toBe('births'));
});
