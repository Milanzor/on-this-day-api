<?php


namespace App\EventSource\Base\Interface;

use App\DataObject\EventDataObject;
use Illuminate\Support\Collection;

interface EventSourceInterface
{

    public function fetch(): self;

    public function transformToEventDataObject(...$params): EventDataObject;

    public function collectEventDataObjects(): Collection;

    public static function fake(?array ...$constructor_params): self;

}
