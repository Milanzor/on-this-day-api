<?php

namespace App\EventSource\Wikimedia;

use App\DataObject\EventDataObject;
use App\Enum\Category;
use App\Enum\Source;
use App\EventSource\Base\AbstractEventSource;
use App\EventSource\Base\Interface\EventSourceInterface;
use App\EventSource\Wikimedia\Enum\WikimediaCategory;
use App\EventSource\Wikimedia\Enum\WikimediaLanguage;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WikimediaEventSource extends AbstractEventSource implements EventSourceInterface
{

    /**
     * @var array|mixed
     */
    private array $events = [];

    public function __construct(readonly private ?string $accessToken = null)
    {
        //
    }

    public function fetch(): void
    {
        $requestUrl = sprintf(
            "wikipedia/%s/onthisday/all/%s/%s",
            WikimediaLanguage::from($this->language->value)->value,
            $this->month,
            $this->day,
        );

        $client = $this->client();

        $response = retry(10, function () use ($requestUrl, $client) {

            $response = $client->get($requestUrl);

            if (!$response->successful()) {

                throw new RuntimeException('Wikimedia API returned a non-200 response');
            }

            return $response;

        }, 200);

        if ($response->successful()) {
            $this->events = $response->json();
        }
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

        return Http::withHeaders($headers)->baseUrl('https://api.wikimedia.org/feed/v1/');
    }

    public function collectEventDataObjects(): Collection
    {

        return collect($this->events)->map(function (array $events, string $category) {
            return collect($events)->map(function (array $event) use ($category) {
                return $this->transformToEventDataObject($category, $event);
            })->filter();

        })->flatten(1);
    }

    public function transformToEventDataObject(...$params): EventDataObject
    {
        [$category, $event] = $params;

        return new EventDataObject(
            description: $event['text'],
            month: $this->month,
            day: $this->day,
            category: match (WikimediaCategory::from($category)) {
                WikimediaCategory::Selected, WikimediaCategory::Events => Category::Regular,
                WikimediaCategory::Births => Category::Births,
                WikimediaCategory::Deaths => Category::Deaths,
                WikimediaCategory::Holidays => Category::Holidays,
            },
            language: $this->language,
            source: Source::Wikimedia,
            url: null,
            year: $event['year'] ?? null,
        );
    }

    public function willFake(): self
    {

        Http::fake([
            'wikipedia/*/onthisday/*' => Http::response([
                'births' => [
                    [
                        'year' => 1930,
                        'text' => 'Piet',
                    ],
                    [
                        'year' => 1930,
                        'text' => 'Jan',
                    ],
                ],
                'deaths' => [
                    [
                        'year' => 2000,
                        'text' => 'Piet',
                    ],
                    [
                        'year' => 2023,
                        'text' => 'Jan',
                    ],
                ],
                'events' => [
                    [
                        'year' => 1231,
                        'text' => 'Something happened',
                    ],
                    [
                        'year' => 1234,
                        'text' => 'Something else happened',
                    ],
                ],
                'holidays' => [
                    [
                        'year' => null,
                        'text' => 'Christmas holiday',
                    ],
                    [
                        'year' => null,
                        'text' => 'Christmas holiday',
                    ],
                ],
                'selected' => [
                    [
                        'year' => 2021,
                        'text' => 'Highlight of the year was today',
                    ],
                    [
                        'year' => 2022,
                        'text' => 'Highlight of the year was today',
                    ],
                ],
            ]),
        ]);

        return $this;
    }

}
