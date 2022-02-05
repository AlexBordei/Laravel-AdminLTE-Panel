@extends('layouts.app')

@section('title', 'Send new sms')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Send sms</h3>
                </div>
                <form action="{{ route('sms.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                {{--Phone number--}}
                                <div class="form-group">
                                    <label for="to">Phone number</label>
                                    <input type="text" name="to" class="form-control" placeholder="Phone number">
                                </div>
                            </div>
                            <div class="col-md-6">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" rows="3" name="message" placeholder="Enter ..."></textarea>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
