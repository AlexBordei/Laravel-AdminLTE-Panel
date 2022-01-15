@extends('layouts.app')

@section('title', 'Edit subscription')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add student</h3>
                </div>
                <form action="{{ route('subscription.update', ['subscription' => $data]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Student</label>
                                    <select class="form-control" name="student_id" id="students-list">
                                        <option value="">Select student...</option>
                                        @foreach($data['students'] as $student)
                                            <option value="{{ $student->id }}" {{ $data['student']->id === $student->id ? 'selected="selected"' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
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
                                            <option value="{{ $subscription_type->id }}" {{ $data['subscription_type']->id === $subscription_type->id ? 'selected="selected"' : '' }}>{{ $subscription_type->name }}</option>
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
                                            <option value="{{ $status }}" {{ $data['status'] === $status ? 'selected="selected"' : '' }}>{{ ucfirst($status) }}</option>
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
                                                    <input type="text" name="starting" class="form-control datetimepicker-input col-6" data-target="#subscription_starting_date" value="{{ $data->starting }}">
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
                        <div class="row">
                            <div class="form-group">
                                <label>Payment id</label>
                                <select class="form-control" name="payment_id" id="payment_id-list">
                                    <option value="">Select payment id...</option>
                                    @foreach($data['payments_ids'] as $payment_id)
                                        <option value="{{ $payment_id->id }}" {{ $data->payment_id=== $payment_id->id ? 'selected="selected"' : '' }}>{{ $payment_id->id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
