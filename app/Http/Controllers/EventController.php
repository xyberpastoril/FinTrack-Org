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
        $events = Event::where('semester_id', 1)->get();

        return view('events.index', compact('events'));
    }

    public function store(StoreEventRequest $request)
    {
        $validated = $request->validated();

        Event::create([
            'name' => $validated['name'],
            'date_from' => $validated['date_from'],
            'date_to' => $validated['date_to'],
            'semester_id' => 1,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }
}
