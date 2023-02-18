@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Events') }}</div>

                <div class="card-body">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        Create
                    </button>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                            <tr>
                                <th scope="row">{{ $event->id }}</th>
                                <td>{{ $event->name }}</td>
                                <td>{{ $event->date }}</td>
                                <td>
                                    @if($event->status == 'closed')
                                        <span class="badge bg-secondary">Closed</span>
                                    @elseif($event->status == 'timein')
                                        <span class="badge bg-primary">Time-In</span>
                                    @elseif($event->status == 'timeout')
                                        <span class="badge bg-success">Time-Out</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('events.scan', $event->id) }}"
                                        class="btn btn-sm btn-primary @if($event->status == 'closed') disabled @endif">Scan</a>
                                    {{-- <a href="{{ route('events.show', $event->id) }}"
                                            class="btn btn-sm btn-primary">Show</a> --}}
                                    @if(Auth::user()->is_admin)
                                    <a href="{{ route('events.edit', $event->id) }}"
                                        class="btn btn-sm btn-secondary">Edit</a>
                                    <form action="{{ route('events.logs.export', $event->id) }}" method="post"
                                            style="display: inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-secondary">Export Logs</button>
                                    </form>
                                    <form action="{{ route('events.destroy', $event->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" disabled=true>Delete</button>
                                    </form>
                                    @endif
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

{{-- create modal --}}
<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="create" action="{{ route('events.store') }}" method="post">
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
