$(function () {
    var teacher_id = 0;
    var student_id = 0;
    var room_id = 0;
    var instrument_id = 0;

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

    var events = createEvents();
    var calendar = new  Calendar(calendarEl, {
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
            info.event.setExtendedProp('room_id', draggedEl.data('room_id'));
            info.event.setExtendedProp('instrument', draggedEl.data('instrument'));

            if (checkIfRoomAlreadyBooked(info) === false) {
                var starting_date = info.event.start.getDate() + '-' + (info.event.start.getMonth() + 1) + '-' + info.event.start.getFullYear() +
                    ' ' + ('00' + info.event.start.getHours()).slice(-2) + ":" +
                    ('00' + info.event.start.getMinutes()).slice(-2);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_calendar_token"]').attr('content')
                    }
                });

                var data = {
                    'starting': starting_date
                };

                if (confirm("Do you want this event to be recurrent weekly?")) {
                    data['recurrent'] = 'yes';
                    should_refresh = true;
                    if (confirm("Do you want this timeslot to be reserved into the future?")) {
                        data['timeslot_reservations'] = 'yes';
                    }
                }

                $.ajax({
                    url: "/calendar/schedule/" + draggedEl.data('event'),
                    type: "POST",
                    dataType: "json",
                    data: data,
                    success: function (data) {
                        info.draggedEl.parentNode.removeChild(info.draggedEl);
                        location.reload();
                    },
                    error: function () {
                        info.revert();
                    }
                });
            }
            else {
                alert('There is already an event booked for the same timeslot and same room!');
                info.revert();
            }

        },
        eventDrop: function(info) {
            // alert(info.event.title + " was dropped on " + info.event.start.toISOString());

            if (!confirm("Are you sure about this change?")) {
                info.revert();
                return;
            }
            if (checkIfRoomAlreadyBooked(info) === false) {
                var starting_date = ('00' + info.event.start.getDate()).slice(-2) + '-' + ('00' + (info.event.start.getMonth() + 1)).slice(-2) + '-' + info.event.start.getFullYear() +
                    ' ' + ('00' + info.event.start.getHours()).slice(-2) + ":" +
                    ('00' + info.event.start.getMinutes()).slice(-2);

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_calendar_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "/calendar/update/" + info.event.extendedProps.event,
                    type: "POST",
                    dataType: "json",
                    data: {
                        'starting': starting_date
                    },
                    success: function (data) {
                        //TODO: add a success info notice
                    },
                    error: function () {
                        info.revert();
                    }
                });
            } else {
                alert('There is already an event booked for the same timeslot and same room!');
                info.revert();
            }
        },
        eventClick: function(info) {
            // if(info.event.extendedProps.type === 'event') {
                var content = '<table>';
                $.each(info.event.extendedProps, function (key, value) {
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
            // }
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

    function checkIfRoomAlreadyBooked(info) {
        var status = false;
        var events = calendar.getEvents();
        var eventDate = new Date(info.event.start.getFullYear(), info.event.start.getMonth(), info.event.start.getDate(), info.event.start.getHours(), info.event.start.getMinutes(), 0, 0);

        events.forEach(function(event) {
            if(event.extendedProps['event'] !== info.event.extendedProps['event']) {
                if(event.start.getTime() === eventDate.getTime()) {

                    var eventRoomId = parseInt(event.extendedProps['room_id']);
                    var newEventRoomId = parseInt(info.event.extendedProps['room_id']);

                   if(eventRoomId === newEventRoomId) {
                       status = true;
                       return false;
                   }
                }
            }
        });

        return status;
    }

    $('.fc-timeGridWeek-button').click();

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

    function createEvents() {
        var events = [];
        var scheduled_events = $('.scheduled-event');

        for(var i = 0; i < scheduled_events.length; i++) {
            var elem = $(scheduled_events[i]);

            if(teacher_id > 0 && teacher_id !== parseInt(elem.data('teacher_id'))) {
                continue;
            }

            if(student_id > 0 && student_id !== parseInt(elem.data('student_id'))) {
                continue;
            }

            if(room_id > 0 && room_id !== parseInt(elem.data('room_id'))) {
                continue;
            }

            if(instrument_id > 0 && instrument_id !== parseInt(elem.data('instrument_id'))) {
                continue;
            }

            var event = {
                start: elem.data('starting'),
                end: elem.data('ending'),
            };
            switch(elem.data('type')) {
                case 'reservation':
                    event.title =  'Reservation: ' + elem.data('student');
                    event.extendedProps = {
                        'room_id': elem.data('room_id')
                    };
                    event.extendedProps.type = 'reservation';
                    event.backgroundColor = '#c0c0c0';
                    break;
                default:
                    event.title = elem.data('subscription') + ' ' + elem.data('student') + ' ' + elem.data('instrument');
                    event.extendedProps = {
                        'subscription': elem.data('subscription'),
                        'event': elem.data('event'),
                        'student': elem.data('student'),
                        'teacher': elem.data('teacher'),
                        'teacher': elem.data('teacher'),
                        'room': elem.data('room'),
                        'room_id': elem.data('room_id'),
                        'instrument': elem.data('instrument'),
                        'edit': elem.data('edit'),
                        'type': 'event'
                    };
                    event.backgroundColor = elem.data('color');
                    break;
            }
            events.push(event);
        }
        return events;
    }

    $('#teacher').select2();
    $('#teacher').on('change', function() {
        teacher_id = parseInt($(this).children(':selected').val());
        update_events();
    });

    $('#student').select2();
    $('#student').on('change', function() {
        student_id = parseInt($(this).children(':selected').val());
        update_events();
    });

    $('#room').select2();
    $('#room').on('change', function() {
        room_id = parseInt($(this).children(':selected').val());
        update_events();
    });

    $('#instrument').select2();
    $('#instrument').on('change', function() {
        instrument_id = parseInt($(this).children(':selected').val());
        update_events();
    });

    function update_events() {
        var events = createEvents();
        calendar.removeAllEvents();
        calendar.addEventSource(events)
        calendar.render();
    }

    $('#reservation_student').select2();
    $('#reservation_student').on('change', function () {
        $('#reserved-events').empty();
        var data = JSON.parse(grouped_reservations);

        if(data[$(this).val()]['reservations'] !== undefined) {
            $('#reserved-events').append($('<button class="btn btn-danger btn-flat" onclick="delete_all_reservations(this)">Delete all reservations</button>'));
            for(var i = 0; i < data[$(this).val()]['reservations'].length; i++) {
                var date = new Date(data[$(this).val()]['reservations'][i]['starting']);
                $('#reserved-events').append(
                    $('<div class="external-event">' + data[$(this).val()]['reservations'][i]['id'] + ' - ' +
                    date.toLocaleString() +
                    '   <div class="remove_reservation">' +
                    '       <span onclick="remove_reservation(this)">x</span>' +
                    '       <form action="/reservation/' + data[$(this).val()]['reservations'][i]['id'] + '" method="post" style="display: none;">' +
                    '          <input type="hidden" name="_method" value="delete">' +
                    '          <input type="hidden" name="_token" value="' + $('meta[name="_calendar_token"]').attr('content') + '">' +
                    '       </form>' +
                    '   </div>' +
                    '</div>'
                ));
            }
        }
    });
});

function remove_reservation(e) {
    $(e).parent().find('form').submit();
}

function delete_all_reservations(e) {

    let text = "Are you sure you want to delete all reservations for selected student?!\nYes or No.";
    if (confirm(text) == true) {
        var student_id = $('#reservation_student').find(':selected').data('student_id');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_calendar_token"]').attr('content')
            }
        });

        $.ajax({
            url: "/reservation/delete_all",
            type: "POST",
            dataType: "json",
            data: {
                'student_id' : student_id
            },
            success: function (data) {
               location.reload();
            },
            error: function () {
                alert("There was an error deleting all reservations");
            }
        });
    }
}
