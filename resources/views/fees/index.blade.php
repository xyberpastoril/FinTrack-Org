@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Semestral Fees') }}</div>

                <div class="card-body">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        Create
                    </button>

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Is Required</th>
                                <th scope="col" class="text-end">Amount</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fees as $fee)
                            <tr>
                                <th scope="row">{{ $fee->id }}</th>
                                <td>{{ $fee->name }}</td>
                                <td>
                                    @if ($fee->is_required)
                                        <span class="badge bg-danger">Yes</span>
                                    @else
                                        <span class="badge bg-success">No</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($fee->amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('fees.edit', $fee->id) }}"
                                        class="btn btn-sm btn-secondary">Edit</a>
                                    {{-- <form action="{{ route('fees.destroy', $fee->id) }}" method="post"
                                        style="display: inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" disabled=true>Delete</button>
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

{{-- create modal --}}
<div class="modal fade" id="createModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Create Fee</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="create" action="{{ route('fees.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="date" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Required?</label>
                        <select class="form-select" aria-label="Default select example" name="is_required">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
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
