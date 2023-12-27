<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{

    public function today(): AnonymousResourceCollection
    {

        $now = now();

        return EventResource::collection(
            Event::query()->where('eventday', $now->day)
                ->where('eventmonth', $now->month)
                ->orderBy('eventyear')
                ->get()
        );
    }

}
