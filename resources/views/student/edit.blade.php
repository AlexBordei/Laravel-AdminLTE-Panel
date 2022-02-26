@extends('layouts.app')

@section('title', 'Edit student')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add student</h3>
                </div>
                <form action="{{ route('student.update', ['student' => $data]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <x-forms.input label="First name" value="{{ $data->first_name }}"/>
                        <x-forms.input label="Last name" value="{{ $data->last_name }}"/>
                        <x-forms.input label="Phone" value="{{ $data->phone }}"/>
                        <x-forms.input.email label="Email" value="{{ $data->email }}"/>
                        <x-forms.input.calendar label="Birth date" value="{{ $data->birth_date }}"/>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
