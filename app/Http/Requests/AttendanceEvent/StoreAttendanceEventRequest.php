<?php

namespace App\Http\Requests\AttendanceEvent;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAttendanceEventRequest extends FormRequest
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
            'name' => ['required'],
            'date' => ['required', 'date'],
            'status' => ['required'],
            'required_logs' => ['required'],
            'fines_amount_per_log' => ['required', 'numeric', 'min:0'],
        ];
    }
}
