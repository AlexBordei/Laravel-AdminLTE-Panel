@extends('layouts.app')

@section('title', 'Create new instrument')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add instrument</h3>
            </div>
            <form action="{{ route('instrument.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    {{--Name--}}
                    <div class="form-group">
                        <label for="first_name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Name">
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
