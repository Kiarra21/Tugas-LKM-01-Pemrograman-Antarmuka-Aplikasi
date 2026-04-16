<?php

namespace App\Http\Requests;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookRequest extends FormRequest
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
     * @return array<string, array<int, string|Rule>|string>
     */
    public function rules(): array
    {
        $bookId = (int) $this->route('book')->id;

        return [
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'author_id' => ['sometimes', 'integer', 'exists:authors,id'],
            'title' => ['sometimes', 'string', 'max:150'],
            'slug' => ['sometimes', 'string', 'max:170', Rule::unique('books', 'slug')->ignore($bookId)],
            'isbn' => ['sometimes', 'string', 'max:20', Rule::unique('books', 'isbn')->ignore($bookId)],
            'published_year' => ['sometimes', 'integer', 'between:1900,2100'],
            'stock' => ['sometimes', 'integer', 'min:0'],
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
