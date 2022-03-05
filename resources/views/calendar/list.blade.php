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
    <!-- Page specific script -->
    <script>
        $(function () {

            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (https://fullcalendar.io/docs/event-object)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex        : 1070,
                        revert        : true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    })

                })
            }

            ini_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date()
            var d    = date.getDate(),
                m    = date.getMonth(),
                y    = date.getFullYear()

            var Calendar = FullCalendar.Calendar;
            var Draggable = FullCalendar.Draggable;

            var containerEl = document.getElementById('external-events');
            var calendarEl = document.getElementById('calendar');

            // initialize the external events
            // -----------------------------------------------------------------

            new Draggable(containerEl, {
                itemSelector: '.external-event',
                eventData: function(eventEl) {
                    return {
                        title: eventEl.innerText,
                        backgroundColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
                        borderColor: window.getComputedStyle( eventEl ,null).getPropertyValue('background-color'),
                        textColor: window.getComputedStyle( eventEl ,null).getPropertyValue('color'),
                    };
                }
            });

            var events = [];
            $('.scheduled-event').each(function() {
                events.push({
                    title: $(this).data('subscription') + ' ' + $(this).data('student') + ' ' + $(this).data('instrument'),
                    start: $(this).data('starting'),
                    end: $(this).data('ending'),
                    extendedProps: {
                        'subscription': $(this).data('subscription'),
                        'event': $(this).data('event'),
                        'student': $(this).data('student'),
                        'teacher': $(this).data('teacher'),
                        'room': $(this).data('room'),
                        'instrument': $(this).data('instrument'),
                        'edit': $(this).data('edit'),
                    }
                });
            });

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left  : 'prev,next today',
                    center: 'title',
                    right : 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                //Random default events
                events: events,
                editable  : true,
                droppable : true, // this allows things to be dropped onto the calendar !!!
                drop: function() {
                    if (!confirm("Are you sure you want to schedule this event?")) {
                        info.revert();
                    }
                },

                eventReceive      : function(info) {
                    var draggedEl = $(info.draggedEl);

                    info.event.setExtendedProp('subscription', draggedEl.data('subscription'));
                    info.event.setExtendedProp('event', draggedEl.data('event'));
                    info.event.setExtendedProp('student', draggedEl.data('student'));
                    info.event.setExtendedProp('teacher', draggedEl.data('teacher'));
                    info.event.setExtendedProp('room', draggedEl.data('room'));
                    info.event.setExtendedProp('instrument', draggedEl.data('instrument'));

                    var starting_date = info.event.start.getDate() + '-' + (info.event.start.getMonth() + 1) + '-' + info.event.start.getFullYear() +
                        ' ' + ('00'+ info.event.start.getHours()).slice(-2) + ":" +
                        ('00'+ info.event.start.getMinutes()).slice(-2);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_calendar_token"]').attr('content')
                        }
                    });

                    var data = {
                        'starting' : starting_date
                    };

                    if (confirm("Do you want this event to be recurrent weekly?")) {
                        data['recurrent'] = 'yes';
                        should_refresh = true;
                    }

                    $.ajax({
                        url:"/calendar/schedule/" + draggedEl.data('event'),
                        type:"POST",
                        dataType:"json",
                        data: data,
                        success:function (data) {
                            info.draggedEl.parentNode.removeChild(info.draggedEl);
                            location.reload();
                        },
                        error: function() {
                            info.revert();
                        }
                    });

                },
                eventDrop: function(info) {
                    // alert(info.event.title + " was dropped on " + info.event.start.toISOString());

                    if (!confirm("Are you sure about this change?")) {
                        info.revert();
                        return;
                    }

                    var starting_date = ('00'+ info.event.start.getDate()).slice(-2)+ '-' +  ('00'+ (info.event.start.getMonth() + 1)).slice(-2) + '-' + info.event.start.getFullYear() +
                        ' ' + ('00'+ info.event.start.getHours()).slice(-2) + ":" +
                        ('00'+ info.event.start.getMinutes()).slice(-2);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_calendar_token"]').attr('content')
                        }
                    });
                    console.log(info.event.extendedProps.event);
                    $.ajax({
                        url:"/calendar/update/" + info.event.extendedProps.event,
                        type:"POST",
                        dataType:"json",
                        data: {
                            'starting' : starting_date
                        },
                        success:function (data) {
                            //TODO: add a success info notice
                        },
                        error: function() {
                            info.revert();
                        }
                    });

                },
                eventClick: function(info) {
                    var content = '<table>';
                    $.each(info.event.extendedProps, function(key, value) {
                        content += '<tr>';
                        content += '    <td>' + capitalize(key) + '</td>';
                        content += '    <td>' + value + '</td>';
                        content += '</tr>';
                    });
                    content += '</table>';

                    $('#modal-event-list .modal-title').text(
                        info.event.extendedProps.subscription + ' - ' + info.event.extendedProps.student
                    );
                    $('#modal-event-list .modal-body').html(content);
                    $('#modal-event-list').modal('show');
                    // change the border color just for fun
                    info.el.style.borderColor = 'red';
                },
                eventMouseLeave: function(info) {
                    info.el.style.borderColor = 'white';
                },
                eventMouseEnter: function(info) {
                    info.el.style.borderColor = 'red';
                }
            });

            calendar.render();
            $('.fc-timeGridWeek-button').click();
            // $('#calendar').fullCalendar()

            /* ADDING EVENTS */
            var currColor = '#3c8dbc' //Red by default
            // Color chooser button
            $('#color-chooser > li > a').click(function (e) {
                e.preventDefault()
                // Save color
                currColor = $(this).css('color')
                // Add color effect to button
                $('#add-new-event').css({
                    'background-color': currColor,
                    'border-color'    : currColor
                })
            })
            $('#add-new-event').click(function (e) {
                e.preventDefault();
                // Get value and make sure it is not null
                var val = $('#new-event').val()
                if (val.length == 0) {
                    return
                }

                // Create events
                var event = $('<div />')
                event.css({
                    'background-color': currColor,
                    'border-color'    : currColor,
                    'color'           : '#fff'
                }).addClass('external-event')
                event.text(val)
                $('#external-events').prepend(event)

                // Add draggable funtionality
                ini_events(event)

                // Remove event from text input
                $('#new-event').val('')
            })

            const capitalize = (s) => {
                if (typeof s !== 'string') return ''
                return s.charAt(0).toUpperCase() + s.slice(1)
            }
        })
    </script>
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
                            @if(count($data['events']) > 0)
                                @foreach($data['events'] as $event)
                                    @if($event->status === 'pending')
                                        <div class="external-event bg-info"
                                        data-event="{{$event->id}}"
                                        data-subscription="{{$event->subscription->id}}"
                                        data-student="{{$event->subscription->student->first_name}} {{$event->subscription->student->last_name}}"
                                        data-teacher="{{$event->subscription->teacher->first_name}} {{$event->subscription->teacher->last_name}}"
                                        data-instrument="{{$event->subscription->instrument->name}}"
                                        data-room="{{$event->subscription->room->name}}"
                                        >
                                            {{$event->subscription->id}} -
                                            Student: {{$event->subscription->student->first_name}} {{$event->subscription->student->last_name}}<br>
                                            Teacher: {{$event->subscription->teacher->first_name}} {{$event->subscription->teacher->last_name}}<br>
                                            Instrument: {{$event->subscription->instrument->name}}<br>
                                            Room: {{$event->subscription->room->name}}
                                        </div>
                                    @endif
                                @endforeach
                                @else
                                    There are no events to be scheduled
                            @endif
                        </div>
                        <div id="scheduled-events">
                            @if(count($data['events']) > 0)
                                @foreach($data['events'] as $event)
                                    @if($event->status === 'scheduled' || $event->status === 'confirmed')
                                        <div class="scheduled-event"
                                             data-event="{{$event->id}}"
                                             data-subscription="{{$event->subscription->id}}"
                                             data-student="{{$event->subscription->student->first_name}} {{$event->subscription->student->last_name}}"
                                             data-teacher="{{$event->subscription->teacher->first_name}} {{$event->subscription->teacher->last_name}}"
                                             data-instrument="{{$event->subscription->instrument->name}}"
                                             data-room="{{$event->subscription->room->name}}"
                                             data-starting="{{$event->starting->format('Y-m-d\TH:i:00')}}"
                                             data-ending="{{$event->ending->format('Y-m-d\TH:i:00')}}"
                                             data-edit='<a href="/event/{{$event->id}}/edit">Click here to edit</a>'
                                        ></div>
                                    @endif
                                @endforeach
                            @else
                                There are no events to be scheduled
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create Event</h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
                            <ul class="fc-color-picker" id="color-chooser">
                                <li><a class="text-primary" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-warning" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-success" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-danger" href="#"><i class="fas fa-square"></i></a></li>
                                <li><a class="text-muted" href="#"><i class="fas fa-square"></i></a></li>
                            </ul>
                        </div>
                        <!-- /btn-group -->
                        <div class="input-group">
                            <input id="new-event" type="text" class="form-control" placeholder="Event Title">

                            <div class="input-group-append">
                                <button id="add-new-event" type="button" class="btn btn-primary">Add</button>
                            </div>
                            <!-- /btn-group -->
                        </div>
                        <!-- /input-group -->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
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
