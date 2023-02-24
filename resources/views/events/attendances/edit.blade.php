@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Attendance Event') }}</div>

                <div class="card-body">
                    <form id="update" action="{{ route('events.attendances.update', ['event' => $event->id, 'attendance' => $attendance->id]) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $attendance->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required value="{{ $attendance->date }}">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="closed" @if($attendance->status == 'closed') selected @endif>Closed</option>
                                <option value="timein" @if($attendance->status == 'timein') selected @endif>Time-In</option>
                                <option value="timeout" @if($attendance->status == 'timeout') selected @endif>Time-Out</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="required_logs" class="form-label">Required Logs</label>
                            <select class="form-select" id="required_logs" name="required_logs">
                                <option value="0" @if($attendance->required_logs == '0') selected @endif>0</option>
                                <option value="1" @if($attendance->required_logs == '1') selected @endif>1</option>
                                <option value="2" @if($attendance->required_logs == '2') selected @endif>2</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fines_amount_per_log" class="form-label">Fines Amount / Log</label>
                            <input type="number" class="form-control" id="fines_amount_per_log" name="fines_amount_per_log" required value="{{ number_format($attendance->fines_amount_per_log, 2) }}">
                        </div>
                    </form>

                    <button type="submit" form="update" class="btn btn-primary">Submit</button>
                    <a href="{{ route('events.show', $event->id) }}" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
