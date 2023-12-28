<?php

namespace App\Wikimedia;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Wikimedia
{

    private const BASE_URL = 'https://api.wikimedia.org/feed/v1/wikipedia/';
    private string $language = 'en';

    public function __construct(readonly private ?string $accessToken = null)
    {
        //
    }

    public function on_this_day()
    {
        return $this->client()->get($this->formatRequestUrl('onthisday'));

    }

    private function client(): PendingRequest
    {

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'OnThisDayObsidianFeed (milanvanas@gmail.com)',
        ];

        if ($this->accessToken) {
            $headers['Authorization'] = "Bearer {$this->accessToken}";
        }

        return Http::withHeaders($headers);
    }

    private function formatRequestUrl(string $type): string
    {
        return match ($type) {
            'onthisday' => $this->formatOnThisDayRequestUrl(),
            default => throw new Exception('Invalid request type'),
        };
    }

    private function formatOnThisDayRequestUrl(): string
    {
        $now = now();
        return sprintf("%s%s/onthisday/all/%s/%s", self::BASE_URL, $this->getLanguage(), $now->month, $now->day);
    }

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;
        return $this;
    }
}
