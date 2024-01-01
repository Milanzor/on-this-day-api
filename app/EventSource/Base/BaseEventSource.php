<?php

namespace App\EventSource\Base;

use App\Enum\Language;
use App\EventSource\Base\Interface\EventSourceInterface;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class BaseEventSource implements EventSourceInterface
{
    private Language $language;

    private int $month;

    private int $day;


    public function getBirthdays(Response $response): Collection
    {
        return collect();
    }

    public function getDeaths(Response $response): Collection
    {
        return collect();
    }

    public function getHolidays(Response $response): Collection
    {
        return collect();
    }

    public function getRegulars(Response $response): Collection
    {
        return collect();
    }

    public function setLanguage(Language $language): void
    {
        $this->language = $language;
    }

    public function setMonth(int $month): void
    {
        $this->month = $month;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function doRequest(): Response
    {
        return Http::withHeaders($this->headers())
            ->get($this->formatRequestUrl());
    }

    public function headers(): array
    {
        return [];
    }

    public function formatRequestUrl(): string
    {
        return sprintf("change-me/%s/%d/%d", $this->language->value, $this->month, $this->day);
    }

    public function client(): PendingRequest
    {
        return Http::withHeaders([])->baseUrl('http://localhost/');
    }
}
