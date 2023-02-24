<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrolledStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id',
        'degree_program_id',
        'student_id',
        'year_level',
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function degreeProgram()
    {
        return $this->belongsTo(DegreeProgram::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function attendanceEventLogs()
    {
        return $this->hasMany(AttendanceEventLog::class);
    }
}
