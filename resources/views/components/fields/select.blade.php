<x-wireforms::fields
    :name="$name"
    :id="$id"
    :required="$required"
    :label="$label"
    :show-label="$showLabel"
    :help="$help"
    :key="$key"
    {{ $attributes->whereDoesntStartWith(['x-', 'wire:model', 'wire:change']) }}
>
    <div @class(["relative flex w-full", 'text-gray-700' => !$isSelected(null) && !$errors->has($id), 'text-gray-400' => $isSelected(null)])>
        @isset($prepend)
            {{ $prepend }}
        @endisset

        <select name="{{ $name }}"
                id="{{ $name }}"
                x-ref="input"
                @class([
                    'block w-full px-3 py-1.5 border duration-300 transition-all sm:text-sm focus:outline-none focus:shadow-full rounded-sm disabled:bg-gray-200',
                    'border-gray-200 placeholder-gray-400 focus:ring-primary-300 focus:border-primary-300 focus:shadow-primary-100/50' => !$errors->has($id),
                    'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-300 focus:border-red-300 focus:shadow-red-100/75' => $errors->has($id),
                    $innerClass
                ])
                @if($required) required="required" @endif
                @disabled($disabled)
                @if($multiple) multiple @endif
                @if($placeholder) placeholder="{{ $placeholder }}" @endif
                wire:ignore
            {{ $attributes->whereStartsWith(['x-', 'wire:model', 'wire:change']) }}
        >
            @if($nullable)
                <option value="" wire:key="{{ $name }}-null">{{ $placeholder ?? __('wireforms::form.please_select') }}</option>
            @endif

            @foreach($options as $key => $option)
                <option value="{{ $key }}" wire:key="{{ $name }}-{{ $key }}" @selected($isSelected($key))>{{ $option }}</option>
            @endforeach
        </select>
        @isset($slot)
            {{ $slot }}
        @endisset
        @isset($append)
            {{ $append }}
        @endisset
    </div>
</x-wireforms::fields>
