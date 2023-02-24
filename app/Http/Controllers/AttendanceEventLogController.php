<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceEventLogExport;
use App\Http\Requests\AttendanceEventLog\StoreAttendanceEventLogByStudentIdRequest;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\AttendanceEventLog\StoreAttendanceEventLogRequest;
use App\Models\AttendanceEvent;
use App\Models\AttendanceEventLog;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceEventLogController extends Controller
{
    public function refreshCountAjax(Event $event, AttendanceEvent $attendanceEvent)
    {
        // Get event log count for current $event->status
        $logCount = $event->logs()->where('status', $attendanceEvent->status)->count();
        $studentCount = Student::count();

        return response()->json([
            'event' => $attendanceEvent,
            'log_count'=> $logCount,
            'student_count' => $studentCount
        ]);
    }

    public function searchStudentAjax(Event $event, AttendanceEvent $attendance, $query = null)
    {
        $logs = Student::select(
                'enrolled_students.id',
                'students.first_name',
                'students.last_name',
                'enrolled_students.year_level',
                'degree_programs.abbr',
                'attendance_event_logs.id as log_id',
            )
            ->leftJoin('enrolled_students', function($join) use ($event){
                $join->on('students.id', '=', 'enrolled_students.student_id')
                    ->where('enrolled_students.semester_id', '=', $event->semester_id);
            })
            ->leftJoin('degree_programs', 'enrolled_students.degree_program_id', '=', 'degree_programs.id')
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timein'
            ->leftJoin('attendance_event_logs', function($join) use ($attendance){
                $join->on('enrolled_students.id', '=', 'attendance_event_logs.enrolled_student_id')
                    ->where('attendance_event_logs.status', '=', $attendance->status)
                    ->where('attendance_event_logs.attendance_event_id', '=', $attendance->id);
            })->whereEncrypted('id_number', 'like', "%$query%")
            ->orWhereEncrypted('first_name', 'like', "%$query%")
            ->orWhereEncrypted('last_name', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json([
            'students' => $logs,
            'event' => $attendance,
        ]);
    }

    public function storeAjax(StoreAttendanceEventLogRequest $request, Event $event, AttendanceEvent $attendance)
    {
        if($attendance->status == 'closed')
        {
            return response()->json([
                'message' => 'Event is closed.',
            ], 400);
        }

        $log = $attendance->logs()->updateOrCreate([
            'enrolled_student_id' => $request->student->id,
            'status' => $attendance->status,
            'logged_by_user_id' => Auth::id(),
        ]);

        // Get event log count for current $event->status
        $logCount = $attendance->logs()->where('status', $attendance->status)->count();
        $studentCount = Student::count();

        return response()->json([
            'message' => 'Event log created successfully.',
            'student' => $request->student,
            'enrolled_student' => $request->enrolled_student,
            'log' => $log,
            'event' => $attendance,
            'log_count'=> $logCount,
            'student_count' => $studentCount
        ]);
    }

    public function storeByStudentIdAjax(StoreAttendanceEventLogByStudentIdRequest $request, Event $event, AttendanceEvent $attendance)
    {
        if($attendance->status == 'closed')
        {
            return response()->json([
                'message' => 'Event is closed.',
            ], 400);
        }

        $log = $attendance->logs()->updateOrCreate([
            'enrolled_student_id' => $request->student_id,
            'status' => $attendance->status,
        ], [
            'logged_by_user_id' => Auth::id(),
        ]);

        // Get event log count for current $event->status
        $logCount = $attendance->logs()->where('status', $attendance->status)->count();
        $studentCount = Student::count();

        return response()->json([
            'message' => 'Event log created successfully.',
            'student' => $request->student,
            'degree_program' => $request->student->degreeProgram,
            'log' => $log,
            'event' => $attendance,
            'log_count'=> $logCount,
            'student_count' => $studentCount
        ]);
    }

    public function destroyAjax(AttendanceEventLog $log)
    {
        $log->delete();

        return response()->json([
            'message' => 'Event log deleted successfully.',
        ]);
    }

    public function export(Event $event, AttendanceEvent $attendance)
    {
        return Excel::download(new AttendanceEventLogExport($event, $attendance), "attendance-event-logs-{$attendance->id}-{$attendance->name}.xlsx");
    }
}
