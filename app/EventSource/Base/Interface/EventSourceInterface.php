<?php


namespace App\EventSource\Base\Interface;

use App\Enum\Language;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;

interface EventSourceInterface
{
    public function getBirthdays(Response $response): Collection;

    public function getDeaths(Response $response): Collection;

    public function getHolidays(Response $response): Collection;

    public function getRegulars(Response $response): Collection;

    public function client(): PendingRequest;

    public function doRequest(): Response;

    public function formatRequestUrl(): string;

    public function headers(): array;
    
    public function setLanguage(Language $language): void;

    public function setMonth(int $month): void;

    public function setDay(int $day): void;

}
