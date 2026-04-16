<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['sometimes', 'integer', 'exists:books,id'],
            'borrower_name' => ['sometimes', 'string', 'max:120'],
            'borrower_email' => ['sometimes', 'email', 'max:255'],
            'borrowed_at' => ['sometimes', 'date'],
            'due_date' => ['sometimes', 'date', 'after_or_equal:borrowed_at'],
            'returned_at' => ['nullable', 'date'],
            'status' => ['sometimes', 'in:borrowed,returned,late'],
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
