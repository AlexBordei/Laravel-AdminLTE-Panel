@extends('layouts.app')

@section('title', 'Studenti')

@section('content')
    @if(session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger">
                {{ $error }}
            </div>
        @endforeach
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/student/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add student" /></a>
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
                            <td>{{ (new Carbon\Carbon($elem->birth_date))->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ url('/student/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                <form action="{{ url('/student', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
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
