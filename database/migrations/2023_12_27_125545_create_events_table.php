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
            $table->string('language')->nullable();
            $table->string('hash')->unique();
            $table->integer('day');
            $table->integer('month');
            $table->integer('year')->nullable();
            $table->string('category');
            $table->longText('description')->nullable();
            $table->index(['day', 'month', 'language']);
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
