<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEventLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'enrolled_student_id',
        'attendance_event_id',
        'status',
        'logged_by_user_id',
    ];

    public function enrolledStudent()
    {
        return $this->belongsTo(EnrolledStudent::class);
    }

    public function attendanceEvent()
    {
        return $this->belongsTo(AttendanceEvent::class);
    }

    public function loggedUser()
    {
        return $this->belongsTo(User::class, 'logged_by_user_id');
    }
}
