<?php

namespace App\Http\Requests;

use App\Enum\Category;
use App\Enum\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class EventsThatHappenedOnRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'limit' => 'sometimes|integer|min:1|max:100',
            'category' => [
                'sometimes',
                'nullable',
                new Enum(Category::class)
            ],
            'language' => [
                'sometimes',
                'nullable',
                new Enum(Language::class)
            ]
        ];
    }
}
