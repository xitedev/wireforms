<x-wireforms::fields
    :name="$name"
    :id="$id"
    :required="$required"
    :label="$label"
    :show-label="$showLabel"
    :help="$help"
    :key="$key"
    {{ $attributes->whereDoesntStartWith(['data', 'x-', 'wire:model', 'wire:change']) }}
>
    <div class="w-full">
        <input type="file"
               name="{{ $name }}"
               id="{{ $id }}"
               @if($placeholder)
                   placeholder="{{ $placeholder }}"
               @endif
               @class([
                'block w-full px-3 py-1.5 border duration-300 transition-all sm:text-sm focus:outline-none focus:shadow-full rounded-sm disabled:bg-gray-200',
                'border-gray-200 text-gray-700 placeholder-gray-400 focus:ring-primary-300 focus:border-primary-300 focus:shadow-primary-100/50' => !$errors->has($id),
                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-300 focus:border-red-300 focus:shadow-red-100/75' => $errors->has($id),
                $innerClass
               ])
               @if($required) required="required" @endif
            @disabled($disabled)
            {{ $attributes->whereStartsWith(['data', 'wire:model', 'wire:change', 'x-']) }}
        >
        @if($value)
            <a href="{{ $value->getUrl() }}" class="inline-flex space-x-1 items-center text-xs text-gray-500 pt-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                <span>{{ $value->file_name }}</span>
            </a>
        @endif
        @isset($slot)
            {{ $slot }}
        @endisset
    </div>
</x-wireforms::fields>
