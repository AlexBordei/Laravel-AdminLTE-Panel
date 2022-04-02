@extends('layouts.app')

@section('title', 'Create new student')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add student</h3>
            </div>
            <form action="{{ route('student.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <x-forms.input label="First name"/>
                    <x-forms.input label="Last name"/>
                    <x-forms.input label="Phone"/>
                    <x-forms.input label="Phone2"/>
                    <x-forms.input.email label="Email"/>
                    <x-forms.input.calendar label="Birth date"/>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
