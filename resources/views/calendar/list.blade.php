@extends('layouts.app')

@section('title', 'Calendar')

@section('header')
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.css"/>

    <!-- If you use the default popups, use this. -->
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.css"/>
    <link rel="stylesheet" type="text/css" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.css"/>
@endsection

@section('footer')
    <script src="https://uicdn.toast.com/tui.code-snippet/v1.5.2/tui-code-snippet.min.js"></script>
    <script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.js"></script>
    <script>
        var calendar = new tui.Calendar(document.getElementById('calendar'), {
            defaultView: 'week',
            taskView: false,    // Can be also ['milestone', 'task']
            scheduleView: true,  // Can be also ['allday', 'time']
            template: {
                milestone: function (schedule) {
                    return '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' + schedule.bgColor + '">' + schedule.title + '</span>'
                },
                milestoneTitle: function () {
                    return '<span class="tui-full-calendar-left-content">MILESTONE</span>'
                },
                task: function (schedule) {
                    return '#' + schedule.title
                },
                taskTitle: function () {
                    return '<span class="tui-full-calendar-left-content">TASK</span>'
                },
                allday: function (schedule) {
                    return getTimeTemplate(schedule, true)
                },
                alldayTitle: function () {
                    return '<span class="tui-full-calendar-left-content">ALL DAY</span>'
                },
                time: function (schedule) {
                    return '<strong>' + moment(schedule.start.getTime()).format('HH:mm') + '</strong> ' + schedule.title
                },
                goingDuration: function (schedule) {
                    return '<span class="calendar-icon ic-travel-time"></span>' + schedule.goingDuration + 'min.'
                },
                comingDuration: function (schedule) {
                    return '<span class="calendar-icon ic-travel-time"></span>' + schedule.comingDuration + 'min.'
                },
                monthMoreTitleDate: function (date, dayname) {
                    var day = date.split('.')[2]

                    return '<span class="tui-full-calendar-month-more-title-day">' + day + '</span> <span class="tui-full-calendar-month-more-title-day-label">' + dayname + '</span>'
                },
                monthMoreClose: function () {
                    return '<span class="tui-full-calendar-icon tui-full-calendar-ic-close"></span>'
                },
                monthGridHeader: function (dayModel) {
                    var date = parseInt(dayModel.date.split('-')[2], 10)
                    var classNames = ['tui-full-calendar-weekday-grid-date ']

                    if (dayModel.isToday) {
                        classNames.push('tui-full-calendar-weekday-grid-date-decorator')
                    }

                    return '<span class="' + classNames.join(' ') + '">' + date + '</span>'
                },
                monthGridHeaderExceed: function (hiddenSchedules) {
                    return '<span class="weekday-grid-more-schedules">+' + hiddenSchedules + '</span>'
                },
                monthGridFooter: function () {
                    return ''
                },
                monthGridFooterExceed: function (hiddenSchedules) {
                    return ''
                },
                monthDayname: function (model) {
                    return (model.label).toString().toLocaleUpperCase()
                },
                weekDayname: function (model) {
                    return '<span class="tui-full-calendar-dayname-date">' + model.date + '</span>&nbsp;&nbsp;<span class="tui-full-calendar-dayname-name">' + model.dayName + '</span>'
                },
                weekGridFooterExceed: function (hiddenSchedules) {
                    return '+' + hiddenSchedules
                },
                dayGridTitle: function (viewName) {

                    // use another functions instead of 'dayGridTitle'
                    // milestoneTitle: function() {...}
                    // taskTitle: function() {...}
                    // alldayTitle: function() {...}

                    var title = ''
                    switch (viewName) {
                        case 'milestone':
                            title = '<span class="tui-full-calendar-left-content">MILESTONE</span>'
                            break
                        case 'task':
                            title = '<span class="tui-full-calendar-left-content">TASK</span>'
                            break
                        case 'allday':
                            title = '<span class="tui-full-calendar-left-content">ALL DAY</span>'
                            break
                    }

                    return title
                },
                schedule: function (schedule) {

                    // use another functions instead of 'schedule'
                    // milestone: function() {...}
                    // task: function() {...}
                    // allday: function() {...}

                    var tpl

                    switch (category) {
                        case 'milestone':
                            tpl = '<span class="calendar-font-icon ic-milestone-b"></span> <span style="background-color: ' + schedule.bgColor + '">' + schedule.title + '</span>'
                            break
                        case 'task':
                            tpl = '#' + schedule.title
                            break
                        case 'allday':
                            tpl = getTimeTemplate(schedule, true)
                            break
                    }

                    return tpl
                },
                collapseBtnTitle: function () {
                    return '<span class="tui-full-calendar-icon tui-full-calendar-ic-arrow-solid-top"></span>'
                },
                timezoneDisplayLabel: function (timezoneOffset, displayLabel) {
                    var gmt, hour, minutes

                    if (!displayLabel) {
                        gmt = timezoneOffset < 0 ? '-' : '+'
                        hour = Math.abs(parseInt(timezoneOffset / 60, 10))
                        minutes = Math.abs(timezoneOffset % 60)
                        displayLabel = gmt + getPadStart(hour) + ':' + getPadStart(minutes)
                    }

                    return displayLabel
                },
                timegridDisplayPrimayTime: function (time) {
                    // will be deprecated. use 'timegridDisplayPrimaryTime'
                    var meridiem = 'am'
                    var hour = time.hour

                    if (time.hour > 12) {
                        meridiem = 'pm'
                        hour = time.hour - 12
                    }

                    return hour + ' ' + meridiem
                },
                timegridDisplayPrimaryTime: function (time) {
                    var meridiem = 'am'
                    var hour = time.hour

                    if (time.hour > 12) {
                        meridiem = 'pm'
                        hour = time.hour - 12
                    }

                    return hour + ' ' + meridiem
                },
                timegridDisplayTime: function (time) {
                    return getPadStart(time.hour) + ':' + getPadStart(time.hour)
                },
                timegridCurrentTime: function (timezone) {
                    var templates = []

                    if (timezone.dateDifference) {
                        templates.push('[' + timezone.dateDifferenceSign + timezone.dateDifference + ']<br>')
                    }

                    templates.push(moment(timezone.hourmarker).format('HH:mm a'))

                    return templates.join('')
                },
                popupIsAllDay: function () {
                    return 'All Day'
                },
                popupStateFree: function () {
                    return 'Free'
                },
                popupStateBusy: function () {
                    return 'Busy'
                },
                titlePlaceholder: function () {
                    return 'Subject'
                },
                locationPlaceholder: function () {
                    return 'Location'
                },
                startDatePlaceholder: function () {
                    return 'Start date'
                },
                endDatePlaceholder: function () {
                    return 'End date'
                },
                popupSave: function () {
                    return 'Save'
                },
                popupUpdate: function () {
                    return 'Update'
                },
                popupDetailDate: function (isAllDay, start, end) {
                    var isSameDate = moment(start).isSame(end)
                    var endFormat = (isSameDate ? '' : 'YYYY.MM.DD ') + 'hh:mm a'

                    if (isAllDay) {
                        return moment(start).format('YYYY.MM.DD') + (isSameDate ? '' : ' - ' + moment(end).format('YYYY.MM.DD'))
                    }

                    return (moment(start).format('YYYY.MM.DD hh:mm a') + ' - ' + moment(end).format(endFormat))
                },
                popupDetailLocation: function (schedule) {
                    return 'Location : ' + schedule.location
                },
                popupDetailUser: function (schedule) {
                    return 'User : ' + (schedule.attendees || []).join(', ')
                },
                popupDetailState: function (schedule) {
                    return 'State : ' + schedule.state || 'Busy'
                },
                popupDetailRepeat: function (schedule) {
                    return 'Repeat : ' + schedule.recurrenceRule
                },
                popupDetailBody: function (schedule) {
                    return 'Body : ' + schedule.body
                },
                popupEdit: function () {
                    return 'Edit'
                },
                popupDelete: function () {
                    return 'Delete'
                }
            },
            month: {
                daynames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                startDayOfWeek: 1,
                narrowWeekend: true
            },
            week: {
                daynames: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                startDayOfWeek: 1,
                narrowWeekend: true,
                hourStart: 10,
                hourEnd: 21
            }
        });
        calendar.createSchedules([
            {
                id: '1',
                calendarId: '1',
                title: 'my schedule',
                category: 'time',
                dueDateClass: '',
                start: '2022-02-18T22:30:00+09:00',
                end: '2022-02-19T02:30:00+09:00'
            },
            {
                id: '2',
                calendarId: '1',
                title: 'second schedule',
                category: 'time',
                dueDateClass: '',
                start: '2022-02-18T17:30:00+09:00',
                end: '2022-02-19T17:31:00+09:00'
            }
        ]);
    </script>
@endsection

@section('content')
    <div id="menu">
    <span id="menu-navi">
        <button type="button" class="btn btn-default btn-sm move-today" data-action="move-today">Today</button>
        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-prev">
          <i class="calendar-icon ic-arrow-line-left" data-action="move-prev"></i>
        </button>
        <button type="button" class="btn btn-default btn-sm move-day" data-action="move-next">
          <i class="calendar-icon ic-arrow-line-right" data-action="move-next"></i>
        </button>
      </span>
        <span id="renderRange" class="render-range"></span>
    </div>

    <div id="calendar"></div>

@endsection
