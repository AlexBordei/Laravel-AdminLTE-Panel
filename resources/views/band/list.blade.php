@extends('layouts.app')

@section('title', 'List bands')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/band/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add band" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                        <table class="table table-hover text-nowrap" id="mainTable">
                            <thead>
                            <tr>
                                <th>Band name</th>
                                <th>Students</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $data as $elem )
                                <tr>
                                    <td><a href="/band/{{ $elem->name }}">{{ $elem->name }}</a></td>
                                    <td>
                                        @if(! empty($elem->students))
                                            <ul>
                                            @foreach($elem->students as $student)
                                               <li>{{ $student->first_name }} {{ $student->last_name  }}</li>
                                            @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('/band/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                        <form action="{{ url('/band', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
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
