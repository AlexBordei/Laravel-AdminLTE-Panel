@extends('layouts.app')

@section('title', 'List subscription types')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/subscription_type/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add subscription type" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                    <table class="table table-hover text-nowrap" id="mainTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Number of sessions</th>
                            <th>Duration</th>
                            <th>Number of instruments</th>
                            <th>Number of students</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $elem )
                        <tr>
                            <td>{{ $elem->id }}</td>
                            <td>{{ $elem->name }}</td>
                            <td>{{ $elem->price }}</td>
                            <td>{{ $elem->sessions_number }}</td>
                            <td>{{ $elem->duration }}</td>
                            <td>{{ $elem->instruments_number }}</td>
                            <td>{{ $elem->students_number }}</td>
                            <td>
                                <a href="{{ url('/subscription_type/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                <form action="{{ url('/subscription_type', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
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
