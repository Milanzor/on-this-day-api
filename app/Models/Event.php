<?php

namespace App\Models;

use App\Casts\EventDescriptionCast;
use App\Enum\Category;
use App\Enum\Language;
use App\Enum\Source;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'day' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'description' => EventDescriptionCast::class,
        'hash' => 'string',
        'url' => 'string',
        'category' => Category::class,
        'language' => Language::class,
        'source' => Source::class,
    ];

    protected $fillable = [
        'day',
        'month',
        'year',
        'category',
        'language',
        'source',
        'url',
        'hash',
        'happiness',
        'description',
    ];
}
