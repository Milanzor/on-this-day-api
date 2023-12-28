<?php

namespace App\Models;

use App\Enum\Eventtype;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $casts = [
        'eventday' => 'integer',
        'eventmonth' => 'integer',
        'eventyear' => 'integer',
        'eventtype' => Eventtype::class,
    ];

    protected $fillable = [
        'eventday',
        'eventmonth',
        'eventyear',
        'eventtype',
        'eventdescription',
    ];
}
