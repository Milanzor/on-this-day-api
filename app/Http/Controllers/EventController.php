<?php

namespace App\Http\Controllers;

use App\Enum\EventCategory;
use App\Enum\EventLanguage;
use App\Http\Resources\EventResource;
use App\Repository\EventRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\QueryParam;
use Knuckles\Scribe\Attributes\UrlParam;

class EventController extends Controller
{

    #[Endpoint("Fetch events that happened on a specific day")]
    #[UrlParam(name: 'month', description: 'The month of the event', example: 12)]
    #[UrlParam(name: 'day', description: 'The day of the event', example: 31)]
    #[QueryParam(name: 'limit', type: 'integer', description: 'The limit of the events', required: false, example: 10)]
    #[QueryParam(name: 'category', description: 'The category of the event', required: false, example: 'births', enum: EventCategory::class)]
    #[QueryParam(name: 'language', description: 'The language of the event', required: false, example: 'en', enum: EventLanguage::class)]
    public function that_happened_on(
        Request $request,
        EventRepository $eventRepository,
        string $month,
        string $day
    ): AnonymousResourceCollection
    {
        return EventResource::collection(
            $eventRepository->fetchEvents(
                (int) $month,
                (int) $day,
                EventLanguage::tryFrom($request->query('language', 'en')),
                EventCategory::tryFrom($request->query('category', 'selected')),
                (int) $request->query('limit', 10),
            )
        );
    }

}
