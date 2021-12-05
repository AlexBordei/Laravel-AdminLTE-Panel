@extends('layouts.app')

@section('title', 'Edit instrument')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add instrument</h3>
                </div>
                <form action="{{ route('instrument.update', ['instrument' => $data]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        {{--Name--}}
                        <div class="form-group">
                            <label for="first_name">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $data->name }}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
