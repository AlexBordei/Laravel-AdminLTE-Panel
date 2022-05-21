@extends('layouts.app')

@section('title', 'Edit subscription')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit subscription</h3>
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
                                    <label>Teacher</label>
                                    <select class="form-control" name="teacher_id" id="teachers-list">
                                        <option value="">Select teacher...</option>
                                        @foreach($data['teachers'] as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $data['teacher']->id === $teacher->id ? 'selected="selected"' : '' }}>{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Instrument</label>
                                    <select class="form-control" name="instrument_id"  id="instruments-list">
                                        <option value="">Select instrument...</option>
                                        @foreach($data['instruments'] as $instrument)
                                            <option value="{{ $instrument->id }}" {{ $data['instrument']->id === $instrument->id ? 'selected="selected"' : '' }}>{{ $instrument->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Room</label>
                                    <select class="form-control" name="room_id"  id="rooms-list">
                                        <option value="">Select room...</option>
                                        @foreach($data['rooms'] as $room)
                                            <option value="{{ $room->id }}" {{ $data['room']->id === $room->id ? 'selected="selected"' : '' }}>{{ $room->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
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
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Starting date:</label>
                                    <div class="input-group date" id="subscription_starting_date" data-target-input="nearest">
                                        <div class="row">
                                            <input type="text" name="starting" class="form-control datetimepicker-input col-6" data-target="#subscription_starting_date" value="{{ $data['starting']->format('d/m/Y H:i') }}">
                                            <div class="input-group-append col-6" data-target="#subscription_starting_date" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="comment">Comment</label>
                                    <textarea name="comment" id="comment" cols="30" rows="10" class="form-control">{{ $data->comment  }}</textarea>
                                </div>
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
