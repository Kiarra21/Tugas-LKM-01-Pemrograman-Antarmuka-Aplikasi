<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreBookRequest extends FormRequest
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
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'author_id' => ['required', 'integer', 'exists:authors,id'],
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:170', 'unique:books,slug'],
            'isbn' => ['required', 'string', 'max:20', 'unique:books,isbn'],
            'published_year' => ['required', 'integer', 'between:1900,2100'],
            'stock' => ['required', 'integer', 'min:0'],
            'synopsis' => ['nullable', 'string'],
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation error.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
