@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Event') }}</div>

                <div class="card-body">
                    <form id="update" action="{{ route('users.update', $user->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required value="{{ $user->name }}">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="username" class="form-control" id="username" name="username" required value="{{ $user->username }}">
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position" required value="{{ $user->position }}">
                        </div>
                        <div class="mb-3">
                            <label for="is_admin" class="form-label">Role</label>
                            <select class="form-select" id="is_admin" name="is_admin">
                                <option value="0" @if(!$user->is_admin) selected @endif>User</option>
                                <option value="1" @if($user->is_admin) selected @endif>Admin</option>
                            </select>
                        </div>
                    </form>

                    <button type="submit" form="update" class="btn btn-primary">Submit</button>
                    <a href="{{ route('users.index') }}" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
