<?php

namespace App\Console\Commands;

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Wikimedia\WikimediaLanguageEnum;
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
        $WikimediaLanguage = WikimediaLanguageEnum::English;

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
                $EventCategory = Category::Other;
            }

            return collect($items)->map(function ($item) use ($EventCategory, $now, $WikimediaLanguage) {

                return new EventDataObject(
                    $item['text'],
                    $now->month,
                    $now->day,
                    $EventCategory,
                    Language::from($WikimediaLanguage->value),
                    $item['year'] ?? null,
                );

            })->toArray();


        })->flatten(1);

        $eventRepository->insertManyEvents($events);


    }
}
