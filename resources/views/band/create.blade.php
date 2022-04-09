@extends('layouts.app')

@section('title', 'Create new band')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add band</h3>
                </div>
                <form action="{{ route('band.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.input label="Band name" name="name"/>
                            </div>
                            <div class="col-md-6">
                                @component(
                                    'components.forms.checkbox',
                                    [
                                        'data' => $data['students'],
                                        'label' => 'Available students',
                                        'name'=> 'students'
                                    ]
                                )
                                @endcomponent
                            </div>
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
