@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Event') }}</div>

                <div class="card-body">
                    <form id="update" action="{{ route('events.update', $event->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $event->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date" name="date_from" required value="{{ $event->date_from }}">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date From</label>
                            <input type="date" class="form-control" id="date" name="date_to" required value="{{ $event->date_to }}">
                        </div>
                    </form>

                    <button type="submit" form="update" class="btn btn-primary">Submit</button>
                    <a href="{{ route('events.index') }}" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
