<?php

namespace App\Models;

use App\Enum\Category;
use App\Enum\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'day' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
        'category' => Category::class,
        'description' => 'string',
        'language' => Language::class,
    ];

    protected $fillable = [
        'day',
        'month',
        'year',
        'eventtype',
        'description',
    ];
}
