@extends('layouts.app')

@section('title', 'Create new teacher')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add teacher</h3>
            </div>
            <form action="{{ route('teacher.store') }}" method="POST">
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
                        <div class="input-group date" id="teacher_birth_date" data-target-input="nearest">
                            <div class="row">
                                <input type="text" name="birth_date" class="form-control datetimepicker-input col-6" data-target="#teacher_birth_date">
                                <div class="input-group-append col-6" data-target="#teacher_birth_date" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="calendar_color">Calendar color</label>
                        <input type="color" class="form-control" list="presets" name="calendar_color" value="#a47ae2">
                        <datalist id="presets">
                            @foreach($data['colors'] as $color)
                                <option value="{{ $color }}">{{ $color }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    {{--Google Calendar id--}}
                    <div class="form-group">
                        <label for="google_calendar_id">Google Calendar Id</label>
                        <input type="text" name="google_calendar_id" class="form-control" placeholder="Google Calendar Id">
                    </div>
                    {{--Instrument ids--}}
                    <div class="form-group">
                        <label>Instruments</label>
                        <select multiple="" class="custom-select" name="instrument_ids[]">
                            @foreach($data['instruments'] as $instrument)
                                <option value="{{ $instrument->id }}">{{ $instrument->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Room id--}}
                    <div class="form-group">
                        <label for="room_id">Room</label>
                        <div class="form-group">
                            @foreach($data['rooms'] as $room)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="room_id" value="{{ $room->id }}">
                                    <label class="form-check-label">{{ $room->name }}</label>
                                </div>
                            @endforeach
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
