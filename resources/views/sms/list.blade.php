@extends('layouts.app')

@section('title', 'List SMS\'s')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/sms/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Send SMS" /></a>
                    Android Gateway last seen: {{!empty($data->sms_service_status) ? $data->sms_service_status : 'N/A'}}
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                        <table class="table table-hover text-nowrap" id="mainTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Error</th>
                                <th>Created</th>
                                <th>Updated</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $data as $elem )
                                <tr>
                                    <td>{{ $elem->id }}</td>
                                    <td>{{ $elem->from }}</td>
                                    <td>{{ $elem->to }}</td>
                                    <td>{{ Str::words($elem->message, 5) }}</td>
                                    <td>{{ ucfirst($elem->status )}}</td>
                                    <td>{{ (empty($elem->error) && $elem->status === 'sent')? 'Ok' : $elem->error }}</td>
                                    <td>{{ $elem->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $elem->updated_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <a href="{{ url('/sms/' . $elem->id) }}"><button class="btn btn-secondary btn-flat">View</button></a>
                                        <form action="{{ url('/sms/resend', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
                                            <input class="btn btn-primary btn-flat" type="submit" value="Resend" />
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
