@extends('layouts.app')

@section('title', 'Showing SMS -> ' . $data->to)

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">From {{$data->from}} to {{$data->to}}</h3>
        </div>
        <div class="card-body">
            <p><strong>ID:</strong> {{$data->id}}</p>
            <p><strong>From:</strong> {{$data->from}}</p>
            <p><strong>To:</strong> {{$data->to}}</p>
            <p><strong>Message:</strong> {{$data->message}}</p>
            <p><strong>Status:</strong> {{$data->status}}</p>
            <p><strong>Error:</strong> {{$data->error}}</p>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            <a href="{{ url('/sms/') }}"><button class="btn btn-secondary btn-flat">Go back to SMS's</button></a>
            <form action="{{ url('/sms/resend', ['id' => $data->id]) }}" method="post" style="display: inline-block">
                <input class="btn btn-primary btn-flat" type="submit" value="Resend" />
                @csrf
            </form>
        </div>
        <!-- /.card-footer-->
    </div>
@endsection
