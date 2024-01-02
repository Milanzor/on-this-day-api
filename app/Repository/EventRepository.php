<?php

namespace App\Repository;

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use App\EventSource\Base\Interface\EventSourceInterface;
use App\EventSource\Wikimedia\Enum\WikimediaLanguage;
use App\EventSource\Wikimedia\WikimediaEventSource;
use App\Models\Event;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use RuntimeException;

readonly class EventRepository
{

    public function importFromAllEventSources(Language $language, int $month, int $day): void
    {

        collect([
            (new WikimediaEventSource(config('services.wikimedia.access_token')))
        ])
            ->map(fn(EventSourceInterface $eventSource) => $eventSource
                ->setLanguage($language)
                ->setMonth($month)
                ->setDay($day)
            )
            ->each([$this, 'import'])
            ->empty();

    }


    public function import(EventSourceInterface $eventSource): Collection
    {

        // Launch the event source
        $eventSource->fetch();

        // Format the events into a Collection of EventDataObjects
        return $eventSource->collectEventDataObjects()
            ->each([$this, 'insertEvent']);

    }

    public function insertEvent(EventDataObject $event): bool|null
    {

        $hash = $event->hash();
        if (Event::query()->where('hash', $hash)->exists()) {
            return null;
        }

        $save_data = [
            'description' => $event->description,
            'month' => $event->month,
            'day' => $event->day,
            'category' => $event->category,
            'language' => $event->language,
            'year' => $event->year,
            'source' => $event->source,
            'url' => $event->url,
            'hash' => $hash,
        ];

        return Event::query()->insert($save_data);

    }

    public function fetchEvents(
        int $month,
        int $day,
        ?Language $language,
        ?Category $category,
        int $limit = 10
    ): Collection {

        if ($language === null) {
            $language = Language::English;
        }

        if ($category === null) {
            $category = Category::Regular;
        }

        $query = Event::query()
            ->where('day', $day)
            ->where('month', $month)
            ->where('language', $language)
            ->where('category', $category)
            ->orderBy('year', 'DESC');

        if (!$query->limit(1)->exists()) {
            $this->importFromAllEventSources($language, $month, $day);
        }

        return $query->limit($limit)->get();
    }
}
