<?php

namespace App\Http\Controllers;

use App\Enum\Category;
use App\Enum\Language;
use App\Http\Requests\EventsThatHappenedOnRequest;
use App\Http\Resources\EventResource;
use App\Repository\EventRepository;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\UrlParam;

class EventController extends Controller
{

    #[Endpoint(
        "Fetch events that happened on a specific day",
        <<<ENDPOINT
Fetch events that happened on a specific day
This endpoint fetches events that happened on a specific day.
ENDPOINT
    )]
    #[UrlParam(name: 'month', type: 'integer', description: 'The month of the events', example: 12)]
    #[UrlParam(name: 'day', type: 'integer', description: 'The day of the events', example: 31)]
    public function that_happened_on(
        EventsThatHappenedOnRequest $request,
        EventRepository $eventRepository,
        int $month,
        int $day
    ): AnonymousResourceCollection
    {


        return EventResource::collection(
            $eventRepository->fetchEvents(
                $month,
                $day,
                Language::tryFrom($request->validated('language', 'en')) ?? Language::English,
                Category::tryFrom($request->validated('category', 'selected')) ?? Category::Regular,
                (int) $request->validated('limit', 10),
            )
        );
    }

}
