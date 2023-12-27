<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller {

    public function today() {
        return EventResource::collection(
            Event::query()->where('eventday', now()->day)
                ->where('eventmonth', now()->month)
                ->orderBy('eventyear')
                ->get()
        );
    }

    public function show(string $id) {
        return new EventResource(
            Event::query()->where('id', $id)
                ->firstOrFail()
        );
    }

}
