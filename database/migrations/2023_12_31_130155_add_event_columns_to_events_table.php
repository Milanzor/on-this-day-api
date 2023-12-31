<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', static function (Blueprint $table) {

            $table->string('eventlanguage')->nullable()->after('eventyear');

            $table->dropIndex('events_eventday_eventmonth_index');

            $table->index(['eventday', 'eventmonth', 'eventlanguage'],
                'events_eventday_eventmonth_eventlanguage_index'
            );

            $table->renameColumn('eventtype', 'eventcategory');

            $table->string('hash')->after('id')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', static function (Blueprint $table) {
            $table->dropColumn('eventlanguage');
        });
    }
};
