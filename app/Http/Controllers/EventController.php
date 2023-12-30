<?php

namespace App\Http\Controllers;

use App\Enum\Eventtype;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{

    public function that_happened_on(Request $request, int $month, int $day): AnonymousResourceCollection
    {
        return EventResource::collection(
            Event::query()
                ->where('eventday', $day)
                ->where('eventmonth', $month)
                ->when(
                    $eventtype = Eventtype::tryFrom($request->query('type', 'selected')),
                    function ($query) use ($eventtype) {
                        return $query->where('eventtype', $eventtype);
                    }
                )
                ->orderBy('eventyear', 'DESC')
                ->limit($request->query('limit', 10))
                ->get()
        );
    }

}
