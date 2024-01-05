<?php

namespace App\Console\Commands;

use App\Enum\Language;
use App\Enum\Source;
use App\Models\Event;
use App\Repository\EventRepository;
use Illuminate\Console\Command;

class PrefetchUpcomingWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prefetch-upcoming-week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prefetch the upcoming week of events';

    /**
     * Execute the console command.
     */
    public function handle(EventRepository $eventRepository): void
    {

        $now = now();
        $nowPlusOneWeek = now()->addWeek();
        while ($now->addDay()->isBefore($nowPlusOneWeek)) {

            if (
                Event::query()
                    ->where('month', $now->month)
                    ->where('day', $now->day)
                    ->whereNotIn('source', [Source::Test, Source::Seed, Source::Manual])
                    ->exists()
            ) {
                continue;
            }

            logger()?->info("Prefetching events for {$now->month}/{$now->day}");

            $eventRepository->importFromAllEventSources(Language::English, $now->month, $now->day);
        }

    }
}
