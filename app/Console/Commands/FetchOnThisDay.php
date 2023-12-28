<?php

namespace App\Console\Commands;

use App\Enum\Eventtype;
use App\Models\Event;
use App\Wikimedia\Wikimedia;
use Exception;
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
    public function handle(Wikimedia $wikimedia)
    {
        $success = false;
        $response = null;

        $response = retry(60, static function () use ($wikimedia) {
            $response = $wikimedia->on_this_day();

            if (!$response->successful()) {
                throw new RuntimeException('Failed to fetch on this day data');
            }

            return $response;

        }, 5000);

        $now = now();

        # Delete all events for today
        Event::query()->where('eventmonth', $now->month)->where('eventday', $now->day)->delete();

        collect($response->json())->each(function ($items, $category) use ($now) {

            $Eventtype = Eventtype::tryFrom($category);

            if (!$Eventtype) {
                $Eventtype = Eventtype::Other;
            }

            collect($items)->each(function ($item) use ($Eventtype, $now) {

                Event::query()->updateOrCreate([
                    'eventtype' => $Eventtype,
                    'eventyear' => $item['year'] ?? null,
                    'eventmonth' => $now->month,
                    'eventday' => $now->day,
                    'eventdescription' => $item['text'],
                ]);

            });
        });

    }
}
