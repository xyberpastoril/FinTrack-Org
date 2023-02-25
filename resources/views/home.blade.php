@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    <h4>Select Semester</h4>
                    <form action="{{ route('home.setSemester') }}" method="POST">
                        @csrf

                        <select class="form-control form-select mb-2" name="semester_id" id="semester_id">
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">
                                    @if($semester->semester == 1)
                                        First Semester,
                                    @elseif($semester->semester == 2)
                                        Second Semester,
                                    @endif
                                    AY {{ $semester->year }} - {{ $semester->year + 1 }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
