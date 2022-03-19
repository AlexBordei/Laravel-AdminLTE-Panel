@extends('layouts.app')

@section('title', 'Send new sms')

@section('footer')
    <script>
        $('#sms_templates-list').on('change', function(){
           if($(this).val().length > 0) {
               $('textarea[name="message"]').parent().hide();
           } else {
               $('textarea[name="message"]').parent().show();
           }
        });
    </script>

@endsection

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
                                <div class="form-group">
                                    <label>Student</label>
                                    <select class="form-control" name="student_id" id="students-list">
                                        <option value="">Select student...</option>
                                        @isset($data['students'])
                                            @foreach($data['students'] as $student)
                                                <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Template</label>
                                    <select class="form-control" name="sms_template_id" id="sms_templates-list">
                                        <option value="">Custom message</option>
                                        @isset($data['sms_templates'])
                                            @foreach($data['sms_templates'] as $sms_templates)
                                                <option value="{{ $sms_templates->id }}">{{ $sms_templates->name }}</option>
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
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
