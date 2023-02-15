@props([
    'name',
    'id',
    'label',
    'required' => false,
    'showLabel' => true,
    'help' => null,
    'key' => null
])

<fieldset
    @if($key) wire:key="{{ $key }}" @endif
    {{ $attributes->class('space-y-1')->only(['class', 'wire:key', 'wire:ignore']) }}
>
    @if($showLabel && $label)
        <label for="{{ $id }}" @class(['block text-sm text-gray-600', 'font-semibold' => $required])>
            {{ $label }}
            @if($required) <span class="text-red-400">*</span> @endif
        </label>
    @endif
    {{ $slot }}

    @if($help)
        <p class="mt-1 text-xs text-gray-500" id="{{ $id }}-description">{{ $help }}</p>
    @endif

    @error($id)
        <p class="mt-1 text-xs text-red-600" id="{{ $id }}-error">{{ \Illuminate\Support\Str::of($message)->replace($id, '') }}</p>
    @enderror
</fieldset>
