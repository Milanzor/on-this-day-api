<?php


namespace App\EventSource\Base\Interface;

use App\DataObject\EventDataObject;
use Illuminate\Support\Collection;

interface EventSourceInterface
{

    public function fetch(): void;

    public function transformToEventDataObject(...$params): EventDataObject;

    public function collectEventDataObjects(): Collection;

    public function fake(): self;

}
