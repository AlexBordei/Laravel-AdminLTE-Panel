@extends('layouts.app')

@section('title', 'Create new SMS Template')

@section('header')
    <style>
        #data_source_models_added, #data_source_fields {
            min-height: 200px;
            overflow: auto;
        }

        #data_source_models_added .component, #data_source_fields .component {
            display: inline-block;
            margin: 5px;
            background-color: #C4C4C4;
            padding: 5px;
            border-radius: 10px;
        }

        #data_source_models_added .component::after {
            content: ' \2612';
            color: red;
        }
    </style>
@endsection

@section('footer')
    <script>
        $(document).ready( function () {
            $('#models-list').select2();

            $('#add_to_data_source_btn').on('click', function(e) {
                e.preventDefault();
                $selected = $('#models-list option:checked').val();

                if($selected.length > 0 ) {
                    if($('.component[data-value="' + $selected  + '"]').length === 0) {
                        $('#data_source_models_added').append($('<div class="component" onClick="remove_data_source(this)" data-value="' + $selected  + '">' + $selected  + '</div>'));

                        $.ajax({
                            url:"/model/" + $selected + "/",
                            type:"GET",
                            dataType:"json",
                            success:function (data) {
                                for(var i =0; i < data['schema'].length; i++){
                                    $('#data_source_fields').append($('<div class="component" data-model="' + $selected  + '" onClick="addValueToMessage(this)">{' + data['model'] + '.' + data['schema'][i] + '}</div>'))
                                }
                            },
                            error: function(e) {
                                alert('error');
                                console.log(e);
                            }
                        });

                    } else {
                        alert('This option was already added');
                    }
                }



            });

        });
        function addValueToMessage(elem) {
            $('textarea[name="message"]').val(function(i, text) {
                return text + $(elem).text();
            });
        }
        function remove_data_source(elem) {
            $('#data_source_fields .component[data-model="' + $(elem).data('value') + '"]').each(
                function () {
                    $('textarea[name="message"]').val(
                        $('textarea[name="message"]').val().replaceAll($(this).text(), '')
                    );
                    $(this).remove();
                }
            );
            $(elem).remove();
        }
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Add SMS Template</h3>
                </div>
                <form action="{{ route('sms_template.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <label for="view">View path</label>
                            <input type="text" name="view" class="form-control" placeholder="View">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="message">Message</label>
                                    <textarea class="form-control" rows="3" name="message"
                                              placeholder="Enter ..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    @isset($data['models'])
                                        <label>Model data source</label>
                                        <select class="form-control" name="model" id="models-list">
                                            <option value="">Select model...</option>
                                        @foreach($data['models'] as $model)
                                            <option value="{{$model}}">{{$model}}</option>
                                        @endforeach
                                        </select>
                                    @endisset
                                    <div class="form-group" style="margin-top: 10px; float: right;">
                                        <button type="submit" class="btn btn-primary" id="add_to_data_source_btn">Add data source</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Data source models added</label>

                                    <div class="form-control" id="data_source_models_added"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Fields available to be used into template</label>

                                    <div class="form-control" id="data_source_fields"></div>
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
