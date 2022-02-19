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
                            <th>Subscription ID</th>
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
                            <td>{{ $elem->student->first_name }} {{ $elem->student->last_name }}</td>
                            <td><a href="{{ url('/subscription/' . $elem->subscription->id) }}">{{ $elem->subscription->id }}</a></td>
                            <td>{{ !empty($elem->starting) ? $elem->starting->format('d-m-Y H:i') : ''}}</td>
                            <td>{{ !empty($elem->ending) ? $elem->starting->format('d-m-Y H:i') : ''}}</td>
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
