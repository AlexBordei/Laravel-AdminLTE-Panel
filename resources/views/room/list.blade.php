@extends('layouts.app')

@section('title', 'List rooms')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/room/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add room" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                    <table class="table table-hover text-nowrap" id="mainTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Instruments</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $elem )
                        <tr>
                            <td>{{ $elem->id }}</td>
                            <td>{{ $elem->name }}</td>
                            <td>
                                @foreach($elem['instruments'] as $instrument)
                                    <a class="btn btn-primary btn-sm" href="/instrument/{{$instrument->id}}">{{$instrument->name}}</a>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ url('/room/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                <form action="{{ url('/room', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
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
