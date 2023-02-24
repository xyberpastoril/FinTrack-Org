<?php

namespace App\Http\Requests\AttendanceEventLog;

use App\Models\EnrolledStudent;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreAttendanceEventLogRequest extends FormRequest
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
            'id_number' => ['required'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if the student exists
            $student = Student::whereEncrypted('id_number', $this->id_number)->first();

            if (!$student) {
                $validator->errors()->add('id_number', 'The student does not exist.');
            }

            // Check if the student is enrolled
            $enrolled = EnrolledStudent::where('student_id', $student->id)
                ->where('semester_id', $this->event->semester_id)
                ->first();

            if(!$enrolled) {
                $validator->errors()->add('id_number', 'The student is not enrolled in the current semester.');
            }

            $enrolled->degreeProgram;

            $this->merge([
                'student' => $student,
                'enrolled_student' => $enrolled,
            ]);
        });
    }
}
