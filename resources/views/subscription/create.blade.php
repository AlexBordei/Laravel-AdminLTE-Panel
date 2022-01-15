@extends('layouts.app')

@section('title', 'Create new subscription')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add subscription</h3>
                </div>
                <form action="{{ route('subscription.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Student</label>
                                    <select class="form-control" name="student_id" id="students-list">
                                        <option value="">Select student...</option>
                                        @foreach($data['students'] as $student)
                                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subscription type</label>
                                    <select class="form-control" name="subscription_type_id" id="subscription_type-list">
                                        <option value="">Select subscription type...</option>
                                        @foreach($data['subscription_types'] as $subscription_type)
                                            <option value="{{ $subscription_type->id }}">{{ $subscription_type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        @foreach($data['statuses'] as $status)
                                            <option value="{{ $status }}" {{ $status === 'pending' ? 'selected="selected"' : '' }}>{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Starting date:</label>
                                                <div class="input-group date" id="subscription_starting_date" data-target-input="nearest">
                                                    <div class="row">
                                                        <input type="text" name="starting" class="form-control datetimepicker-input col-6" data-target="#subscription_starting_date">
                                                        <div class="input-group-append col-6" data-target="#subscription_starting_date" data-toggle="datetimepicker">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
