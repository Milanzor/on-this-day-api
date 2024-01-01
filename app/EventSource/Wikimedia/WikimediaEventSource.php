<?php

namespace App\EventSource\Wikimedia;

use App\Enum\Language;
use App\EventSource\Base\BaseEventSource;
use App\EventSource\Wikimedia\Enum\WikimediaLanguage;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class WikimediaEventSource extends BaseEventSource
{

    private const BASE_URL = 'https://api.wikimedia.org/feed/v1/';

    public function __construct(readonly private ?string $accessToken = null)
    {
        //
    }

    public function client(): PendingRequest
    {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'OnThisDayObsidianFeed (milanvanas@gmail.com)',
        ];

        if ($this->accessToken) {
            $headers['Authorization'] = "Bearer {$this->accessToken}";
        }

        return Http::withHeaders($headers)->baseUrl(self::BASE_URL);
    }

    public function formatRequestUrl(): string
    {
        return sprintf(
            "wikipedia/%s/onthisday/all/%s/%s",
            WikimediaLanguage::from($this->language->value)->value,
            $month,
            $day
        );
    }

    public function getBirthdays(Response $response): Collection
    {
        return collect($response->json()['births']);
    }

    public function getDeaths(Response $response): Collection
    {
        return collect($response->json()['deaths']);
    }

    public function getHolidays(Response $response): Collection
    {
        return collect($response->json()['holidays']);
    }

    public function getRegulars(Response $response): Collection
    {
        return collect($response->json()['selected']);
    }

    public function setLanguage(Language $language): void
    {
        // TODO: Implement setLanguage() method.
    }

    public function setMonth(int $month): void
    {
        // TODO: Implement setMonth() method.
    }

    public function setDay(int $day): void
    {
        // TODO: Implement setDay() method.
    }
}
