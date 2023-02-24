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
}
