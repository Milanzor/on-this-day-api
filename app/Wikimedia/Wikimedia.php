<?php

namespace App\Wikimedia;

use App\Enum\Wikimedia\WikimediaLanguage;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Wikimedia
{

    private const BASE_URL = 'https://api.wikimedia.org/feed/v1/';

    public function __construct(readonly private ?string $accessToken = null)
    {
        //
    }

    public function on_this_day(WikimediaLanguage $languageEnum, int $month, int $day): Response
    {
        return $this
            ->client()
            ->get($this->formatOnThisDayRequestUrl($languageEnum, $month, $day));
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

        return Http::withHeaders($headers)->baseUrl(self::BASE_URL);
    }

    private function formatOnThisDayRequestUrl(WikimediaLanguage $languageEnum, string $month, string $day): string
    {
        return sprintf("wikipedia/%s/onthisday/all/%s/%s", $languageEnum->value, $month, $day);
    }

}
