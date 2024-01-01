<?php

namespace App\Console\Commands;

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use App\Enum\Wikimedia\WikimediaLanguage;
use App\Repository\EventRepository;
use Illuminate\Console\Command;
use RuntimeException;

class FetchOnThisDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-on-this-day';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the on this day data from Wikimedia';

    /**
     * Execute the console command.
     */
    public function handle(EventRepository $eventRepository): void
    {

        $now = now();
        $WikimediaLanguage = WikimediaLanguage::English;

        $response = retry(60, static function () use ($eventRepository, $now, $WikimediaLanguage) {

            $response = $eventRepository->getEventsFromWikimedia(
                $WikimediaLanguage,
                $now->month,
                $now->day,
            );

            if (!$response->successful()) {
                throw new RuntimeException('Failed to fetch on this day data');
            }

            return $response;

        }, 300);

        if (!$response->successful()) {
            throw new RuntimeException('Failed to fetch on this day data after retrying');
        }

        $events = collect($response->json())->map(function ($items, $category) use ($now, $WikimediaLanguage) {

            $EventCategory = Category::tryFrom($category);

            if (!$EventCategory) {
                $EventCategory = Category::Regular;
            }

            return collect($items)->map(function ($item) use ($EventCategory, $now, $WikimediaLanguage) {

                return new EventDataObject(
                    description: $item['text'],
                    month: $now->month,
                    day: $now->day,
                    category: $EventCategory,
                    language: Language::from($WikimediaLanguage->value),
                    source: Source::Wikimedia,
                    url: null,
                    year: $item['year'] ?? null,
                );

            })->toArray();


        })->flatten(1);

        $eventRepository->insertManyEvents($events);


    }
}
