@extends('layouts.app')

@section('title', 'List subscriptions')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/subscription/create') }}"><input class="btn btn-primary btn-flat" type="submit" value="Add subscription" /></a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if(! empty($data))
                        <table class="table table-hover text-nowrap" id="mainTable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Student</th>
                                <th>Subscription type</th>
                                <th>Starting</th>
                                <th>Ending</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $data as $elem )
                                <tr>
                                    <td>{{ $elem->id }}</td>
                                    <td><a href="/student/{{ $elem->student->id }}">{{ $elem->student->first_name }} {{ $elem->student->last_name }}</a></td>
                                    <td><a href="{{ $elem->subscription_type->id }}">{{ $elem->subscription_type->name }}</a></td>
                                    <td>{{ (new Carbon\Carbon($elem->starting))->format('d-m-Y') }}</td>
                                    <td>{{ (new Carbon\Carbon($elem->ending))->format('d-m-Y') }}</td>
                                    <td><a href="{{ $elem->payment_id }}">{{ $elem->payment_id }}</a></td>
                                    <td>
                                        @switch($elem->status)
                                            @case('canceled')
                                                <div class="alert alert-danger alert-dismissible">
                                                    {{ ucfirst($elem->status) }}
                                                </div>
                                            @break
                                            @case('pending')
                                            <div class="alert alert-info alert-dismissible">
                                                {{ ucfirst($elem->status)  }}
                                            </div>
                                            @break
                                            @case('expired')
                                            <div class="alert alert-warning alert-dismissible">
                                                {{ ucfirst($elem->status)  }}
                                            </div>
                                            @break
                                            @case('active')
                                            <div class="alert alert-success alert-dismissible">
                                                {{ ucfirst($elem->status)  }}
                                            </div>
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ url('/subscription/' . $elem->id . '/edit') }}"><button class="btn btn-secondary btn-flat">Edit</button></a>
                                        <form action="{{ url('/subscription', ['id' => $elem->id]) }}" method="post" style="display: inline-block">
                                            <input class="btn btn-danger btn-flat" type="submit" value="Cancel" />
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
