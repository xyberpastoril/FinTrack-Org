@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Item') }}</div>

                <div class="card-body">
                    <form id="update" action="{{ route('items.update', $item->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $item->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="date" name="amount" required value="{{ number_format($item->amount, 2) }}">
                        </div>
                    </form>

                    <button type="submit" form="update" class="btn btn-primary">Submit</button>
                    <a href="{{ route('items.index') }}" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
