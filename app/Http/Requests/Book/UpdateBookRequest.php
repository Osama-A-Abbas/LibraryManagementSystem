<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class   UpdateBookRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:500',
            'genres' => 'required|array|min:1',
            'genres.*' => 'required|exists:genres,id',
            'author' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'published_at' => 'required|date',
            'number_of_copies'=> 'required|integer|min:0|max:999999',
            'is_available' => 'sometimes|boolean',
            'cover_page' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'book_pdf' => 'sometimes|mimes:pdf|max:2048',
        ];
    }
}
