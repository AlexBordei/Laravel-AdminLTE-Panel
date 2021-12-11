@extends('layouts.app')

@section('title', 'Create new event')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add event</h3>
            </div>
            <form action="{{ route('event.store') }}" method="POST">
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
                                <label>Teacher</label>
                                <select class="form-control" name="teacher_id" id="teachers-list">
                                    <option value="">Select teacher...</option>
                                    @foreach($data['teachers'] as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
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
                                        <option value="{{ $instrument->id }}">{{ $instrument->name }}</option>
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
                                        <option value="{{ $room->id }}">{{ $room->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Date and time range -->
                            <div class="form-group">
                                <label>Date and time range:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="far fa-clock"></i></span>
                                    </div>
                                    <input type="text" class="form-control float-right" id="reservationtime" name="time_interval">
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->

                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    @foreach($data['statuses'] as $status)
                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
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
