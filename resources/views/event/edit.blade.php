@extends('layouts.app')

@section('title', 'Update event')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add event</h3>
                </div>
                <form action="{{ route('event.update', ['event' => $data['event']]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Subscription</label>
                                    <select class="form-control" name="subscription_id" id="subscriptions-list">
                                        <option value="">Select subscription...</option>
                                        @foreach($data['subscriptions'] as $subscription)
                                            <option {{$data['event']['subscription']->id === $subscription->id ? 'selected="selected' : ''}} value="{{ $subscription->id }}">{{ $subscription->id }} - {{ $subscription->student->first_name }} {{ $subscription->student->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        @foreach($data['statuses'] as $status)
                                            <option {{$data['event']->status === $status ? 'selected="selected' : ''}} value="{{ $status }}">{{ ucfirst($status) }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
