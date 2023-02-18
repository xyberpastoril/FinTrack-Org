<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\EventLog\StoreEventLogRequest;
use Illuminate\Support\Facades\Auth;

class EventLogController extends Controller
{
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
}
