@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Event Details for {{ $event->name }}</div>

                <div class="card-body">
                    <h4>Attendance Events</h4>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Create
                    </button>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Attendance Event</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Required Logs</th>
                                    <th scope="col">Fines/Log</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($event->attendanceEvents as $attendanceEvent)
                                <tr>
                                    <th scope="row">{{ $attendanceEvent->id }}</th>
                                    <td>{{ $attendanceEvent->name }}</td>
                                    <td>{{ $attendanceEvent->date }}</td>
                                    <td>
                                        @if($attendanceEvent->status == 'closed')
                                            <span class="badge bg-secondary">Closed</span>
                                        @elseif($attendanceEvent->status == 'timein')
                                            <span class="badge bg-primary">Time-In</span>
                                        @elseif($attendanceEvent->status == 'timeout')
                                            <span class="badge bg-success">Time-Out</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($attendanceEvent->required_logs == '0')
                                            <span class="badge bg-success">0</span>
                                        @elseif($attendanceEvent->required_logs == '1')
                                            <span class="badge bg-warning">1</span>
                                        @elseif($attendanceEvent->required_logs == '2')
                                            <span class="badge bg-danger">2</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($attendanceEvent->fines_amount_per_log, 2) }}</td>
                                    <td>
                                        <a href="{{ route('events.attendances.edit', ['event' => $event->id, 'attendance' => $attendanceEvent->id]) }}"
                                            class="btn btn-sm btn-secondary">Edit</a>
                                        {{-- <form action="{{ route('events.remove', [$event->id, $student->id]) }}" method="post"
                                            style="display: inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                        </form> --}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- create modal --}}
<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Attendance Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="create" action="{{ route('events.attendances.store', $event->id) }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="closed">Closed</option>
                            <option value="timein">Time-In</option>
                            <option value="timeout">Time-Out</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="required_logs" class="form-label">Required Logs</label>
                        <select class="form-select" id="required_logs" name="required_logs">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fines_amount_per_log" class="form-label">Fines Amount / Log</label>
                        <input type="number" class="form-control" id="fines_amount_per_log" name="fines_amount_per_log" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="create" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection
