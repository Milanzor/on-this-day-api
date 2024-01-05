<?php

namespace App\Http\Resources;

use App\Enum\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Category $category
 * @property string $description
 * @property ?int $year
 * @property int $happiness
 */
class EventResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year' => $this->year,
            'category' => $this->category,
            'description' => $this->description,
            'happiness' => $this->happiness,
        ];
    }
}
