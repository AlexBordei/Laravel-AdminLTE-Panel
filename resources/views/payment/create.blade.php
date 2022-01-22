@extends('layouts.app')

@section('title', 'Create new payment request')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add payment</h3>
            </div>
            <form action="{{ route('payment.store') }}" method="POST">
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
                                        <option value="{{ $user->id }}">ID: {{ $user->id }} {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{--Amount--}}
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" class="form-control" placeholder="Amount">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            {{--Payment method--}}
                            <div class="form-group">
                                <label>Payment method</label>
                                <select class="form-control" name="payment_method">
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank transfer</option>
                                    <option value="card">Card</option>
                                    <option value="online">Online</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            {{--Status--}}
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="pending">Pending</option>
                                    <option value="paid">Paid</option>
                                    <option value="canceled">Canceled</option>
                                    <option value="postponed">Postponed</option>
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
                                    <option value="">Select subscription id...</option>
                                    @foreach($data['subscriptions'] as $subscription)
                                        <option value="{{ $subscription->id }}">ID: {{ $subscription->id }}</option>
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
                    <button type="submit" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
