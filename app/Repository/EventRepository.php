<?php

namespace App\Repository;

use App\DataObject\EventDataObject;
use App\Enum\Wikimedia\WikimediaLanguageEnum;
use App\Models\Event;
use App\Wikimedia\Wikimedia;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

readonly class EventRepository
{


    public function __construct(
        private Wikimedia $wikimedia
    ) {
    }

    public function getEventsFromWikimedia(
        WikimediaLanguageEnum $wikimediaLanguageEnum,
        string $month,
        string $day
    ): Response {

        return $this->wikimedia->on_this_day($wikimediaLanguageEnum, $month, $day);
    }

    public function insertManyEvents(Collection $events): void
    {
        $events->each([$this, 'insertEvent']);
    }

    public function insertEvent(EventDataObject $event): void
    {

        $hash = $event->hash();
        if (Event::query()->where('hash', $hash)->exists()) {
            return;
        }

        $save_data = [
            'eventdescription' => $event->description,
            'eventmonth' => $event->month,
            'eventday' => $event->day,
            'eventcategory' => $event->category,
            'eventlanguage' => $event->language,
            'eventyear' => $event->year,
            'hash' => $hash,
        ];

        Event::query()->insert(
            $save_data,
        );

    }
}
