<div class="form-group">
    <label for="{{ $name }}">{{$label}}</label>
    <input type="{{ $type  }}" name="{{ $name }}" class="form-control" value="{{ $value }}" placeholder="{{$placeholder}}" {{$required === "true" ? 'required' : ''}}>
</div>
