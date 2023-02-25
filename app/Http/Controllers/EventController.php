<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        // get the events of the latest semester
        $events = Event::where('semester_id', session('semester')->id)->get();

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->attendanceEvents;

        return view('events.show', compact('event'));
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        Event::create([
            'name' => $validated['name'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'semester_id' => session('semester')->id,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    public function update(StoreEventRequest $request, Event $event)
    {
        $validated = $request->validated();

        $event->update([
            'name' => $validated['name'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }
}
