@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Logged Students for {{ $event->name }}</div>

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
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $log)
                            <tr>
                                <th scope="row">{{ $log->id }}</th>
                                <td>{{ $log->student->id_number }}</td>
                                <td>{{ $log->student->last_name }}</td>
                                <td>{{ $log->student->first_name }}</td>
                                <td>{{ $log->student->degreeProgram->abbr }}</td>
                                <td>{{ $log->student->year_level }}</td>
                                <td>
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

<script>
@endsection
