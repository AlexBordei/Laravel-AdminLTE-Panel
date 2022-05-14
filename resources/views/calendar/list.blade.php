@extends('layouts.app')

@section('title', 'Calendar')

@section('header')
    <!-- fullCalendar -->
    <link rel="stylesheet" href="{{ asset("plugins/fullcalendar/main.css") }}">
    <meta name="_calendar_token" content="{{csrf_token()}}" />

@endsection

@section('footer')
    <!-- fullCalendar 2.2.5 -->
    <!-- jQuery UI -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar/main.js') }}"></script>
    <script src="{{ asset('app/js/calendar.js') }}"></script>
    <!-- Page specific script -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="sticky-top mb-3">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Events to be scheduled</h4>
                    </div>
                    <div class="card-body">
                        <!-- the events -->
                        <div id="external-events">
                            @if(count($data['events']['pending']) > 0)
                                @foreach($data['events']['pending'] as $event)
                                        <div class="external-event"  style="background-color: {{!empty($event->subscription->teacher->calendar_color) ? $event->subscription->teacher->calendar_color : '#007bff' }}"
                                        data-event="{{$event->id}}"
                                        data-subscription="{{$event->subscription->id}}"
                                        data-student="{{$event->subscription->student->first_name}} {{$event->subscription->student->last_name}}"
                                        data-teacher="{{$event->subscription->teacher->first_name}} {{$event->subscription->teacher->last_name}}"
                                        data-instrument="{{$event->subscription->instrument->name}}"
                                        data-room="{{$event->subscription->room->name}}"
                                        data-room_id="{{$event->subscription->room->id}}"
                                        >
                                            {{$event->subscription->id}} -
                                            Student: {{$event->subscription->student->first_name}} {{$event->subscription->student->last_name}}<br>
                                            Teacher: {{$event->subscription->teacher->first_name}} {{$event->subscription->teacher->last_name}}<br>
                                            Instrument: {{$event->subscription->instrument->name}}<br>
                                            Room: {{$event->subscription->room->name}}
                                        </div>
                                @endforeach
                                @else
                                    There are no events to be scheduled
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Reservations</h4>
                    </div>
                    <div class="card-body">
                        <!-- the events -->
                        <div id="reservations">
                            @if(count($data['grouped_reservations']) > 0)
                                <label for="reservation_student">Student</label>
                                <select name="reservation" id="reservation_student">
                                    <option value="">Select student...</option>
                                @foreach($data['grouped_reservations'] as $key => $grouped_reservation)
                                    <option value="{{$key}}" data-student_id="{{$grouped_reservation['student_id']}}">{{$grouped_reservation['first_name']}} {{$grouped_reservation['last_name']}}</option>
                                @endforeach
                                </select>
                                <div id="reserved-events">

                                </div>
                            <script>
                              var grouped_reservations = {!! json_encode(json_encode($data['grouped_reservations']), JSON_HEX_TAG) !!};
                            </script>
                            @else
                                There are no reservations in calendar
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <div id="scheduled-events">
                    @if(count($data['events']['scheduled']) > 0)
                        @foreach($data['events']['scheduled'] as $event)
                            <div class="scheduled-event"
                                 data-event="{{$event->id}}"
                                 data-subscription="{{$event->subscription->id}}"
                                 data-student="{{$event->subscription->student->first_name}} {{$event->subscription->student->last_name}}"
                                 data-student_id="{{$event->subscription->student->id}}"
                                 data-teacher="{{$event->subscription->teacher->first_name}} {{$event->subscription->teacher->last_name}}"
                                 data-instrument="{{$event->subscription->instrument->name}}"
                                 data-instrument_id="{{$event->subscription->instrument->id}}"
                                 data-room="{{$event->subscription->room->name}}"
                                 data-room_id="{{$event->subscription->room->id}}"
                                 data-starting="{{$event->starting->format('Y-m-d\TH:i:00')}}"
                                 data-ending="{{$event->ending->format('Y-m-d\TH:i:00')}}"
                                 data-teacher_id="{{$event->subscription->teacher->id}}"
                                 data-color="{{!empty($event->subscription->teacher->calendar_color) ? $event->subscription->teacher->calendar_color : '#007bff'}}"
                                 data-edit='<a href="/event/{{$event->id}}/edit?redirect_calendar=yes">Click here to edit</a>'
                            ></div>
                        @endforeach
                    @endif
                    @if(count($data['reservations']) > 0)
                        @foreach($data['reservations'] as $reservation)
                            @if($reservation->status === 'scheduled')
                                <div class="scheduled-event"
                                     data-type="reservation"
                                     data-student="{{$reservation->student->first_name}} {{$reservation->student->last_name}}"
                                     data-teacher="{{$reservation->teacher->first_name}} {{$reservation->teacher->last_name}}"
                                     data-student_id="{{$event->subscription->student->id}}"
                                     data-starting="{{$reservation->starting->format('Y-m-d\TH:i:00')}}"
                                     data-teacher_id="{{$event->subscription->teacher->id}}"
                                     data-instrument_id="{{$event->subscription->instrument->id}}"
                                     data-room_id="{{$event->subscription->room->id}}"
                                     data-ending="{{$reservation->ending->format('Y-m-d\TH:i:00')}}"
                                ></div>
                            @endif
                        @endforeach
                    @endif
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.col -->

        <div class="col-md-9">
            <div class="card card-default">
                <div class="card-header">Filters</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                @if(isset($data['teachers']))
                                    <label for="teacher">Teacher</label>
                                    <select name="teacher" id="teacher">
                                        <option value="">All</option>
                                        @foreach($data['teachers'] as $teacher)
                                            <option value="{{$teacher->id}}">{{$teacher->first_name}} {{$teacher->last_name}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                @if(isset($data['students']))
                                    <label for="student">Student</label>
                                    <select name="student" id="student">
                                        <option value="">All</option>
                                        @foreach($data['students'] as $student)
                                            <option value="{{$student->id}}">{{$student->first_name}} {{$student->last_name}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                @if(isset($data['rooms']))
                                    <label for="room">Room</label>
                                    <select name="room" id="room">
                                        <option value="">All</option>
                                        @foreach($data['rooms'] as $room)
                                            <option value="{{$room->id}}">{{$room->name}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                @if(isset($data['instruments']))
                                    <label for="instrument">Instrument</label>
                                    <select name="instrument" id="instrument">
                                        <option value="">All</option>
                                        @foreach($data['instruments'] as $instrument)
                                            <option value="{{$instrument->id}}">{{$instrument->name}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-primary">
                <div class="card-body p-0">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <div class="modal fade" id="modal-event-create">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div class="modal fade" id="modal-event-list">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"></h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection
