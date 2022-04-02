@extends('layouts.app')

@section('title', 'List teachers')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/teacher/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add teacher" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                    <table class="table table-hover text-nowrap" id="mainTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Birth date</th>
                            <th>Google Calendar Id</th>
                            <th>Calendar color</th>
                            <th>Instruments</th>
                            <th>Preferred Room</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $elem )
                        <tr>
                            <td>{{ $elem->id }}</td>
                            <td>{{ $elem->first_name }}</td>
                            <td>{{ $elem->last_name }}</td>
                            <td>{{ $elem->phone }}</td>
                            <td>{{ $elem->email }}</td>
                            <td>{{ ! empty($elem->birth_date) ? (new Carbon\Carbon($elem->birth_date))->format('d/m/Y') : '' }}</td>
                            <td>{{ $elem->google_calendar_id }}</td>
                            <td style="background-color: {{ $elem->calendar_color }}; "></td>
                            <td>
                                @if(isset($elem['instruments']))
                                    @foreach($elem['instruments'] as $instrument)
                                        <a class="btn btn-primary btn-sm" href="/instrument/{{$instrument->id}}">{{$instrument->name}}</a>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(isset($elem['room']))
                                    <a class="btn btn-primary btn-sm" href="/room/{{$elem['room']->id}}">{{$elem['room']->name}}</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('/teacher/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                <form action="{{ url('/teacher', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
                                    <input class="btn btn-danger btn-flat" type="submit" value="Delete" />
                                    @method('delete')
                                    @csrf
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
