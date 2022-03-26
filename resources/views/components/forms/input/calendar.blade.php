<div class="form-group">
    <label>{{ $label }}</label>
    <div class="input-group date" id="{{ $name }}_date" data-target-input="nearest">
        <div class="row">
            <input type="{{ $type }}" name="{{ $name }}" class="form-control datetimepicker-input col-6" data-target="#{{ $name  }}_date" placeholder="{{ $placeholder }}" value="{{ $value }}">
            <div class="input-group-append col-6" data-target="#{{ $name  }}_date" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
            </div>
        </div>
    </div>
</div>
@section('footer')
    @if(!empty($value) && $isEdit === false)
        <script>
            $('#{{ $name  }}_date').datetimepicker(
                {
                    viewMode: 'years',
                    format: 'DD/MM/YYYY'
                }
            );
        </script>
    @endif
@endsection
