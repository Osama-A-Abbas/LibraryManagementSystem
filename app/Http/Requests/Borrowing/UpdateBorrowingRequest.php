<?php

namespace App\Http\Requests\Borrowing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorization is handled in the controller for more granular control
     * based on the action type and user relationship to the borrowing.
     */
    public function authorize(): bool
    {
        return true; // Detailed permission checks are in the controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $action = $this->input('action');

        $rules = [
            'action' => 'required|string|in:return,approve,reject,update_status',
        ];

        // Add specific validation rules based on the action
        if ($action === 'update_status') {
            $rules['status'] = 'required|string|in:pending,approved,rejected,returned';
        }

        return $rules;
    }
}
