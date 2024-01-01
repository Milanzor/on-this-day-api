<?php

namespace App\Repository;

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use App\EventSource\Wikimedia\WikimediaEventSource;
use App\Models\Event;
use App\EventSource\Wikimedia\Enum\WikimediaLanguage;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use RuntimeException;

readonly class EventRepository
{


    public function __construct(
        private WikimediaEventSource $wikimedia
    ) {
    }

    public function getEventsFromWikimedia(
        WikimediaLanguage $wikimediaLanguageEnum,
        int $month,
        int $day
    ): Response {

        return retry(5, function () use ($wikimediaLanguageEnum, $month, $day) {

            $response = $this->wikimedia->on_this_day($wikimediaLanguageEnum, $month, $day);
            if (!$response->successful()) {
                throw new RuntimeException('Wikimedia API returned a non-200 response');
            }

            return $response;

        }, 1000);
    }

    public function insertManyEvents(Collection $events): void
    {
        $events->each([$this, 'insertEvent']);
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

    public function importEventsFromWikimedia(
        WikimediaLanguage $wikimediaLanguageEnum,
        int $month,
        int $day
    ): void {

        $response = $this->getEventsFromWikimedia($wikimediaLanguageEnum, $month, $day);

        $events = $response->json();

        $events = collect($events)->map(function (array $events, string $category) use (
            $wikimediaLanguageEnum,
            $month,
            $day
        ) {
            return collect($events)->map(function (array $event) use ($category, $wikimediaLanguageEnum, $month, $day) {
                return new EventDataObject(
                    description: $event['text'],
                    month: $month,
                    day: $day,
                    category: Category::fromWikimediaCategory($category),
                    language: Language::fromWikimediaLanguage($wikimediaLanguageEnum),
                    source: Source::Wikimedia,
                    url: null,
                    year: $event['year'] ?? null,
                );
            });

        })->flatten(1);

        $this->insertManyEvents($events);
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
            $this->importEventsFromWikimedia(WikimediaLanguage::from($language->value), $month, $day);

        }

        return $query->limit($limit)->get();
    }
}
