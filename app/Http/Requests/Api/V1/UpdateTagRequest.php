<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('tags')->ignore($this->tag)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('tags')->ignore($this->tag)
            ],
        ];
    }
}
