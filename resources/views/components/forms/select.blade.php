<div class="form-group">
    <label>{{ $label }}</label>
    <select class="form-control" name="{{ $name }}"  id="{{ $id }}">
        <option value="">Select {{ strtolower( $label ) }}...</option>
        @foreach($options as $key => $option)
            <option value="{{ $key }}">{{ $option }}</option>
        @endforeach
    </select>
</div>
