@props(['name', 'label', 'hint' => null, 'span' => false, 'id' => null])

@php($fid = $id ?? 'f-' . $name)

<div @class(['fld', 'span2' => $span])>
    <label for="{{ $fid }}">{{ $label }}</label>
    {{ $slot }}
    @if ($hint)
        <p class="hint">{{ $hint }}</p>
    @endif
    @error($name)
        <p class="err-msg" id="{{ $fid }}-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
    @enderror
</div>
