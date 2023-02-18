<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventLog\StoreEventLogByStudentIdRequest;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventLog\StoreEventLogRequest;
use App\Models\EventLog;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class EventLogController extends Controller
{
    public function searchStudentAjax(Event $event, $query = null)
    {
        $students = Student::select(
                'students.id',
                'students.first_name',
                'students.last_name',
                'students.year_level',
                'degree_programs.abbr',
                'event_logs.id as log_id',
            )
            ->leftJoin('degree_programs', 'students.degree_program_id', '=', 'degree_programs.id')
            ->leftJoin('event_logs', function($join) use ($event) {
                $join->on('students.id', '=', 'event_logs.student_id')
                    ->where('event_logs.status', '=', $event->status)
                    ->where('event_id', '=', $event->id);
            })
            ->orWhereEncrypted('id_number', 'like', "%$query%")
            ->orWhereEncrypted('first_name', 'like', "%$query%")
            ->orWhereEncrypted('last_name', 'like', "%$query%")
            ->limit(10)
            ->get();

        return response()->json([
            'students' => $students,
        ]);
    }

    public function storeAjax(StoreEventLogRequest $request, Event $event)
    {
        if($event->status == 'closed')
        {
            return response()->json([
                'message' => 'Event is closed.',
            ], 400);
        }

        $log = $event->logs()->updateOrCreate([
            'student_id' => $request->student->id,
            'status' => $event->status,
            'logged_by_user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Event log created successfully.',
            'student' => $request->student,
            'degree_program' => $request->student->degreeProgram,
            'log' => $log,
        ]);
    }

    public function storeByStudentIdAjax(StoreEventLogByStudentIdRequest $request, Event $event)
    {
        if($event->status == 'closed')
        {
            return response()->json([
                'message' => 'Event is closed.',
            ], 400);
        }

        $log = $event->logs()->updateOrCreate([
            'student_id' => $request->student_id,
            'status' => $event->status,
        ], [
            'logged_by_user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Event log created successfully.',
            'student' => $request->student,
            'degree_program' => $request->student->degreeProgram,
            'log' => $log,
        ]);
    }

    public function destroyAjax(EventLog $log)
    {
        $log->delete();

        return response()->json([
            'message' => 'Event log deleted successfully.',
        ]);
    }
}
