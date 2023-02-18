<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $logs = Student::select(
                'students.id',
                'students.id_number',
                'students.first_name',
                'students.last_name',
                'students.year_level',
                'degree_programs.abbr',
                DB::raw('IF(timein_events.id IS NOT NULL, "1", "0") as time_in'),
                DB::raw('IF(timeout_events.id IS NOT NULL, "1", "0") as time_out'),
            )
            ->leftJoin('degree_programs', 'students.degree_program_id', '=', 'degree_programs.id')
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timein'
            ->leftJoin('event_logs as timein_events', function($join) use ($event){
                $join->on('students.id', '=', 'timein_events.student_id')
                    ->where('timein_events.status', '=', 'timein')
                    ->where('timein_events.event_id', '=', $event->id);
            })
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timeout'
            ->leftJoin('event_logs as timeout_events', function($join) use ($event){
                $join->on('students.id', '=', 'timeout_events.student_id')
                    ->where('timeout_events.status', '=', 'timeout')
                    ->where('timeout_events.event_id', '=', $event->id);
            })
            ->get();

        // return $logs;

        return view('events.show', [
            'event' => $event,
            'logs' => $logs,
        ]);
    }

    public function scan(Event $event)
    {
        if($event->status == 'closed')
            return redirect()->route('events.index')->with('error', 'Event is closed.');

        return view('events.scan', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        $event = Event::create($validated);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $event->update($validated);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }
}
