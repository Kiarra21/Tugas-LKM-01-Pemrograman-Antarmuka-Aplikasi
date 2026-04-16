<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'integer', 'exists:books,id'],
            'borrower_name' => ['required', 'string', 'max:120'],
            'borrower_email' => ['required', 'email', 'max:255'],
            'borrowed_at' => ['required', 'date'],
            'due_date' => ['required', 'date', 'after_or_equal:borrowed_at'],
            'returned_at' => ['nullable', 'date', 'after_or_equal:borrowed_at'],
            'status' => ['required', 'in:borrowed,returned,late'],
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
