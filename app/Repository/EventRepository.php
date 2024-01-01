<?php

namespace App\Repository;

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
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
            'description' => $event->description,
            'month' => $event->month,
            'day' => $event->day,
            'category' => $event->category,
            'language' => $event->language,
            'year' => $event->year,
            'hash' => $hash,
        ];

        Event::query()->insert(
            $save_data,
        );

    }

    public function fetchEvents(
        int $month,
        int $day,
        ?Language $eventLanguage,
        ?Category $eventCategory,
        int $limit = 10
    ): Collection {


        return Event::query()
            ->where('day', $day)
            ->where('month', $month)
            ->when($eventLanguage, function ($query) use ($eventLanguage) {
                $query->where('language', $eventLanguage);
            })
            ->when($eventCategory, function ($query) use ($eventCategory) {
                $query->where('category', $eventCategory);
            })
            ->orderBy('year', 'DESC')
            ->limit($limit)
            ->get();
    }
}
