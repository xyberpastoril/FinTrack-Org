<?php

namespace App\Imports;

use App\Models\DegreeProgram;
use App\Models\EnrolledStudent;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $student = Student::whereEncrypted('id_number', $row['id_number'])->first();

        if(!$student) {
            $student = Student::create([
                'id_number' => $row['id_number'] ? $row['id_number'] : "",
                'last_name' => $row['last_name'] ? $row['last_name'] : "",
                'first_name' => $row['first_name'] ? $row['first_name'] : "",
                'middle_name' => $row['middle_name'] ? $row['middle_name'] : null,
            ]);
        }

        // check if degree program exists
        if($row['degree_program']) {
            $degreeProgram = DegreeProgram::whereEncrypted('abbr', $row['degree_program'])->first();

            if(!$degreeProgram) {
                $degreeProgram = DegreeProgram::create([
                    'name' => $row['degree_program'],
                    'abbr' => $row['degree_program'],
                ]);
            }

            EnrolledStudent::firstOrCreate([
                'student_id' => $student->id,
                'semester_id' => session('semester')->id,
            ], [
                'degree_program_id' => $degreeProgram->id,
                'year_level' => $row['year_level'],
            ]);
        }

        return null;
    }
}
