@props([
    'key',
    'value'
])

<li role="option"
    {{ $attributes->class("group relative py-2 px-3") }}
    id="listbox-option-{{ $key }}"
    wire:key="listbox-option-{{ $key }}"
>
    <span @class(['block truncate', 'font-semibold' => $this->isCurrent($key)])>
        {{ $value }}
    </span>

    @if($this->isCurrent($key))
        <span class="text-primary-600 group-focus:text-white group-hover:text-white absolute inset-y-0 right-0 flex items-center pr-4">
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
        </span>
    @endif
</li>
