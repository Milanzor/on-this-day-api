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
        Schema::create('events', static function (Blueprint $table) {
            $table->id();
            $table->integer('eventday');
            $table->integer('eventmonth');
            $table->integer('eventyear')->nullable();
            $table->string('eventtype');
            $table->longText('eventdescription')->nullable();
            $table->text('eventtitle')->nullable();
            $table->index(['eventday', 'eventmonth']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
