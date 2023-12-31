<?php

namespace App\Models;

use App\Enum\EventCategory;
use App\Enum\EventLanguage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'eventday' => 'integer',
        'eventmonth' => 'integer',
        'eventyear' => 'integer',
        'eventcategory' => EventCategory::class,
        'eventdescription' => 'string',
        'eventlanguage' => EventLanguage::class,
    ];

    protected $fillable = [
        'eventday',
        'eventmonth',
        'eventyear',
        'eventtype',
        'eventdescription',
    ];
}
