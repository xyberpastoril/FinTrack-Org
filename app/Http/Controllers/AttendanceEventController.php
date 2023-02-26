<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceEvent;
use App\Http\Requests\AttendanceEvent\StoreAttendanceEventRequest;
use App\Http\Requests\AttendanceEvent\UpdateAttendanceEventRequest;
use App\Models\Event;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class AttendanceEventController extends Controller
{
    public function show(Event $event, AttendanceEvent $attendance)
    {
        $logs = Student::select(
                'students.id',
                'students.id_number',
                'students.first_name',
                'students.last_name',
                'students.middle_name',
                'enrolled_students.year_level',
                'degree_programs.abbr',
                DB::raw('IF(timein_events.id IS NOT NULL, "1", "0") as time_in'),
                DB::raw('IF(timeout_events.id IS NOT NULL, "1", "0") as time_out'),
            )
            ->leftJoin('enrolled_students', 'students.id', '=', 'enrolled_students.student_id')
            ->where('enrolled_students.semester_id', '=', $event->semester_id)
            ->leftJoin('degree_programs', 'enrolled_students.degree_program_id', '=', 'degree_programs.id')
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timein'
            ->leftJoin('attendance_event_logs as timein_events', function($join) use ($attendance){
                $join->on('enrolled_students.id', '=', 'timein_events.enrolled_student_id')
                    ->where('timein_events.status', '=', 'timein')
                    ->where('timein_events.attendance_event_id', '=', $attendance->id);
            })
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timeout'
            ->leftJoin('attendance_event_logs as timeout_events', function($join) use ($attendance){
                $join->on('enrolled_students.id', '=', 'timeout_events.enrolled_student_id')
                    ->where('timeout_events.status', '=', 'timeout')
                    ->where('timeout_events.attendance_event_id', '=', $attendance->id);
            })
            ->get();

        return view('events.attendances.show', [
            'event' => $event,
            'attendance' => $attendance,
            'logs' => $logs,
        ]);
    }

    public function scan(Event $event, AttendanceEvent $attendance)
    {
        if($attendance->status == 'closed')
            return redirect()->route('events.show', $event->id)->with('error', 'Event is closed.');

        // Get event log count for current $event->status
        $logCount = $attendance->logs()->where('status', $attendance->status)->count();
        $studentCount = Student::count();

        return view('events.attendances.scan', [
            'attendance' => $attendance,
            'log_count'=> $logCount,
            'student_count' => $studentCount
        ]);
    }

    public function store(StoreAttendanceEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        AttendanceEvent::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'date' => $validated['date'],
            'status' => $validated['status'],
            'required_logs' => $validated['required_logs'],
            'fines_amount_per_log' => $validated['fines_amount_per_log'],
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Attendance Event created successfully.');
    }

    public function edit(Event $event, AttendanceEvent $attendance)
    {
        return view('events.attendances.edit', [
            'event' => $event,
            'attendance' => $attendance,
        ]);
    }

    public function update(UpdateAttendanceEventRequest $request, Event $event, AttendanceEvent $attendance)
    {
        $validated = $request->validated();

        $attendance->update([
            'name' => $validated['name'],
            'date' => $validated['date'],
            'status' => $validated['status'],
            'required_logs' => $validated['required_logs'],
            'fines_amount_per_log' => $validated['fines_amount_per_log'],
        ]);

        return redirect()->route('events.show', $event->id)->with('success', 'Attendance Event updated successfully.');
    }

    public function destroy(Event $event, AttendanceEvent $attendance)
    {
        $attendance->delete();

        return redirect()->route('events.show', $event->id)->with('success', 'Attendance Event deleted successfully.');
    }
}
