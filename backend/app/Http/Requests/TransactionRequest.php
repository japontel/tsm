<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
     public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'account_number_from' => 'required|string|max:255',
            'account_number_type_from' => 'required|string|max:255',
            'account_number_to' => 'required|string|max:255',
            'account_number_type_to' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:credit,debit',
            'description' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:255',
            'creation_date' => 'required|date',
        ];
    }
}
