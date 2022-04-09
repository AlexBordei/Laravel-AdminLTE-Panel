@extends('layouts.app')

@section('title', 'Edit band')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit band</h3>
                </div>
                <form action="{{ route('band.update', ['band' => $data['band']]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <x-forms.input label="Band name" name="name" value="{{$data['band']->name}}"/>
                            </div>
                            <div class="col-md-6">
                                @component(
                                    'components.forms.checkbox',
                                    [
                                        'data' => $data['students'],
                                        'label' => 'Students',
                                        'name'=> 'students',
                                        'selected_data' => $data['selected_data']
                                    ]
                                )
                                @endcomponent
                            </div>
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
