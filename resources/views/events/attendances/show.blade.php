@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Logged Students for {{ $attendance->name }}</div>

                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">ID Number</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Degree Program</th>
                                <th scope="col">Year Level</th>
                                <th scope="col">Time In</th>
                                <th scope="col">Time Out</th>
                                {{-- <th scope="col">Actions</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                            <tr>
                                <th scope="row">{{ $log->id }}</th>
                                <td>{{ $log->id_number }}</td>
                                <td>{{ $log->last_name }}</td>
                                <td>{{ $log->first_name }}</td>
                                <td>{{ $log->abbr }}</td>
                                <td>{{ $log->year_level }}</td>
                                <td>
                                    @if($log->time_in == 0)
                                        <span class="badge bg-danger">No</span>
                                    @else
                                        <span class="badge bg-success">Yes</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->time_out == 0)
                                        <span class="badge bg-danger">No</span>
                                    @else
                                        <span class="badge bg-success">Yes</span>
                                    @endif
                                </td>
                                {{-- <td> --}}
                                    {{-- <form action="{{ route('events.remove', [$event->id, $student->id]) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form> --}}
                                {{-- </td> --}}
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
@endsection
