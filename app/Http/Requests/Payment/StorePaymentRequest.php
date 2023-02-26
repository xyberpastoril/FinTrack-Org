<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'enrolled_student_id' => ['required'],
            'date' => ['required', 'date'],
            'transaction_items' => ['required', 'array'],
        ];
    }

    // preprocess data
    public function prepareForValidation(): void
    {
        $this->merge([
            'transaction_items' => json_decode($this->transaction_items),
        ]);
    }
}
