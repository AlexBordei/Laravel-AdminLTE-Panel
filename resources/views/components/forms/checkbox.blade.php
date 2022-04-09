<div class="form-group">
    <label>{{ $label }}</label>
    @if(!empty($data))
        @foreach($data as $elem)
            <div class="form-check">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="{{ $name }}[]"
                    value="{{ $elem['value'] }}"
                    @if(!empty($selected_data) && in_array($elem['value'], $selected_data))
                        checked
                    @endif
                >
                <label class="form-check-label">{{ $elem['label'] }}</label>
            </div>
        @endforeach
    @endif
</div>
