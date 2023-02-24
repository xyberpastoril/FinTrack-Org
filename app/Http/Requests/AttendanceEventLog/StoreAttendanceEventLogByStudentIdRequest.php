<?php

namespace App\Http\Requests\AttendanceEventLog;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAttendanceEventLogByStudentIdRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the student exists
            $student = Student::where('id', $this->student_id)->first();

            if (!$student) {
                $validator->errors()->add('student_id', 'The student does not exist.');
            }

            $this->merge([
                'student' => $student,
            ]);
        });
    }
}
