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
use Maatwebsite\Excel\Facades\Excel;

class AttendanceEventLogController extends Controller
{

    public function export(Event $event, AttendanceEvent $attendance)
    {
        return Excel::download(new AttendanceEventLogExport($event, $attendance), "attendance-event-logs-{$attendance->id}-{$attendance->name}.xlsx");
    }
}
