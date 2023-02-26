@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Active Attendance Events</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered ">
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
                                @foreach ($attendanceEvents as $attendanceEvent)
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
                                        @if($attendanceEvent->required_logs == 0)
                                            <span class="badge bg-success">0</span>
                                        @elseif($attendanceEvent->required_logs == 1)
                                            <span class="badge bg-warning">1</span>
                                        @elseif($attendanceEvent->required_logs == 2)
                                            <span class="badge bg-danger">2</span>
                                        @endif
                                    </td>
                                    <td class="text-end">{{ number_format($attendanceEvent->fines_amount_per_log, 2) }}</td>
                                    <td>
                                        <a href="{{ route('events.attendances.scan', ['event' => $attendanceEvent->event_id, 'attendance' => $attendanceEvent->id]) }}"
                                            class="btn btn-sm btn-secondary">Scan</a>
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
@endsection
