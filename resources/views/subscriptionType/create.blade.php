@extends('layouts.app')

@section('title', 'Create new subscription type')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add subscription type</h3>
            </div>
            <form action="{{ route('subscription_type.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" name="price" class="form-control" placeholder="Price">
                    </div>
                    <div class="form-group">
                        <label for="sessions_number">Number of sessions</label>
                        <input type="number" name="sessions_number" class="form-control" placeholder="Number of sessions">
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <input type="number" name="duration" class="form-control" placeholder="Duration">
                    </div>
                    <div class="form-group">
                        <label for="instruments_number">Number of instruments</label>
                        <input type="number" name="instruments_number" class="form-control" placeholder="Number of instruments">
                    </div>
                    <div class="form-group">
                        <label for="students_number">Number of students</label>
                        <input type="number" name="students_number" class="form-control" placeholder="Number of students">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
