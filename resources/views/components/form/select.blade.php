@props([
    'required' =>'',
    'label' => '',
    'error' => ''
])

<div class=" mb-1">
    @if($label)
    <label class="form-label {{ $required }}" for="{{ $attributes->get('id') }}">{{ $label }}</label>
    @endif
    <select {{ $attributes->merge(['class' => 'form-control form-select']) }}>
        {{ $slot }}
    </select>
    @if($error)
    <div class="invalid-feedback d-block text-danger">
        {{ $error }}
    </div>
    @endif
</div>
