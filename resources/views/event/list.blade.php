@extends('layouts.app')

@section('title', 'List events')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/event/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add event" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                    <table class="table table-hover text-nowrap" id="mainTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student</th>
                            <th>Teacher</th>
                            <th>Instrument</th>
                            <th>Room</th>
                            <th>Starting</th>
                            <th>Ending</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $elem )
                        <tr>
                            <td>{{ $elem->id }}</td>
                            <td><a href="{{ url('/student/' . $elem->student->id) }}">{{ $elem->student->first_name }} {{ $elem->student->last_name }}</a></td>
                            <td><a href="{{ url('/teacher/' . $elem->teacher->id) }}">{{ $elem->teacher->first_name }} {{ $elem->teacher->last_name }}</a></td>
                            <td><a href="{{ url('/instrument/' . $elem->instrument->id) }}">{{ $elem->instrument->name }}</a></td>
                            <td><a href="{{ url('/room/' . $elem->room->id) }}">{{ $elem->room->name }}</a></td>
                            <td>{{ $elem->starting->format('d-m-Y H:i') }}</td>
                            <td>{{ $elem->ending->format('d-m-Y H:i') }}</td>
                            <td>{{ ucfirst($elem->status) }}</td>
                            <td>
                                <a href="{{ url('/event/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                <form action="{{ url('/event', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
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
