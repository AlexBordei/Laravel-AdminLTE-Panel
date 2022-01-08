@extends('layouts.app')

@section('title', 'List payments')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/payment/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add payment" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                    <table class="table table-hover text-nowrap" id="mainTable">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Subscription ID</th>
                            <th>Amount</th>
                            <th>Payment method</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Updated</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach( $data as $elem )
                        <tr>
                            <td>{{ $elem->id }}</td>
                            <td>
                                <a href="/user/{{ $elem->user['id'] }}">{{ $elem->user['name'] }}</a>
                            </td>
                            @if(! empty($elem->subscription_id))
                                <td>
                                    <a href="/subscription/{{ $elem->subscription_id }}">{{ $elem->subscription_id }}</a>
                                </td>
                            @else
                                <td>N/A</td>
                            @endif
                            <td>{{ $elem->amount }}</td>
                            <td>{{ $elem->payment_method }}</td>
                            <td>{{ $elem->status }}</td>
                            <td>{{ $elem->created_at }}</td>
                            <td>{{ $elem->updated_at }}</td>
                            <td>
                                <a href="{{ url('/payment/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                <form action="{{ url('/payment', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
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
