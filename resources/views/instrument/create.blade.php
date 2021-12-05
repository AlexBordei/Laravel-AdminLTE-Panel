@extends('layouts.app')

@section('title', 'Create new student')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add student</h3>
            </div>
            <form action="{{ route('student.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    {{--First name--}}
                    <div class="form-group">
                        <label for="first_name">First name</label>
                        <input type="text" name="first_name" class="form-control" placeholder="First name">
                    </div>
                    {{--Last name--}}
                    <div class="form-group">
                        <label for="last_name">Last name</label>
                        <input type="text" name="last_name" class="form-control" placeholder="Last name">
                    </div>
                    {{--Phone--}}
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="Phone">
                    </div>
                    {{--Email--}}
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Email">
                    </div>
                    {{--Birth date--}}
                    <div class="form-group">
                        <label>Birth date:</label>
                        <div class="input-group date" id="student_birth_date" data-target-input="nearest">
                            <div class="row">
                                <input type="text" name="birth_date" class="form-control datetimepicker-input col-6" data-target="#student_birth_date">
                                <div class="input-group-append col-6" data-target="#student_birth_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
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
