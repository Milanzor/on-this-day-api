<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enum\Eventtype;
use Database\Factories\EventFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     */
    public function run(): void {

        EventFactory::new([
            'eventday' => 1,
            'eventmonth' => 1,
            'eventyear' => '2021',
            'eventtitle' => 'New Year\'s Day',
            'externalid' => '54321',
            'eventdescription' => 'New Year\'s Day is the first day of the Gregorian calendar, falling exactly one week after the Christmas Day of the previous year. In modern times, it is January 1st of the year, and is usually a public holiday, often celebrated with fireworks at the stroke of midnight as the new year starts.',
            'eventtype' => Eventtype::Other
        ])->create();

    }
}
