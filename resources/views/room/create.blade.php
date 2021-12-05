@extends('layouts.app')

@section('title', 'Create new room')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add room</h3>
            </div>
            <form action="{{ route('room.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    {{--Room name--}}
                    <div class="form-group">
                        <label for="name">Room name</label>
                        <input type="text" name="name" class="form-control" placeholder="Room name">
                    </div>
                    <div class="form-group">
                        <label>Instruments</label>
                        <select multiple="" class="custom-select" name="instrument_ids[]">
                            @foreach($data['instruments'] as $instrument)
                            <option value="{{ $instrument->id }}">{{ $instrument->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
