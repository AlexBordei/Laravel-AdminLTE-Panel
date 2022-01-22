@extends('layouts.app')

@section('title', 'Edit payment')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit payment</h3>
                </div>
                <form action="{{ route('payment.update', ['payment' => $data]) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                {{--User--}}
                                <div class="form-group">
                                    <label>User</label>
                                    <select class="form-control" name="user_id" id="users-list">
                                        <option value="">Select User...</option>
                                        @foreach($data['users'] as $user)
                                            <option value="{{ $user->id }}" {{$data->user_id === $user->id ? 'selected="selected' : ''}}>ID: {{ $user->id }} {{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{--Amount--}}
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" class="form-control" placeholder="Amount" value="{{$data->amount}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                {{--Payment method--}}
                                <div class="form-group">
                                    <label>Payment method</label>
                                    <select class="form-control" name="payment_method">
                                        <option value="cash" {{$data['payment_method'] === 'cash' ? 'selected="selected' : ''}}>Cash</option>
                                        <option value="bank_transfer" {{$data['payment_method'] === 'bank_transfer' ? 'selected="selected' : ''}}>Bank transfer</option>
                                        <option value="card" {{$data['payment_method'] === 'card' ? 'selected="selected' : ''}}>Card</option>
                                        <option value="online" {{$data['payment_method'] === 'online' ? 'selected="selected' : ''}}>Online</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{--Status--}}
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control" name="status">
                                        <option value="pending" {{$data['status'] === 'pending' ? 'selected="selected' : ''}}>Pending</option>
                                        <option value="paid" {{$data['status'] === 'paid' ? 'selected="selected' : ''}}>Paid</option>
                                        <option value="canceled" {{$data['status'] === 'canceled' ? 'selected="selected' : ''}}>Canceled</option>
                                        <option value="postponed" {{$data['status'] === 'postponed' ? 'selected="selected' : ''}}>Postponed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                {{--Subscription id--}}
                                <div class="form-group">
                                    <label>Subscription ID</label>
                                    <select class="form-control" name="subscription_id" id="subscription_id-list">
                                        @if(!empty($data['selected_sub_id']))
                                            <option value="{{$data['selected_sub_id']->id}}">ID: {{$data['selected_sub_id']->id}}</option>
                                        @else
                                            <option value="">Select subscription id...</option>
                                        @endif
                                        @foreach($data['subscriptions'] as $subscription)
                                            <option value="{{ $subscription->id }}" {{$data->subscription_id === $subscription->id ? 'selected="selected' : ''}}>ID: {{ $subscription->id }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="activate_subscription">
                                    <label class="form-check-label" for="activate_subscription">Activate subscription</label>
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
