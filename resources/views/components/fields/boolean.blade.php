<fieldset
    {{ $attributes->class('space-y-1')->only('class') }}
    wire:key="{{ $key }}"
>
    <div class="flex items-center space-x-2">
        <button type="button"
                @click="on = !on"
                :aria-pressed="on !== null ? on.toString() : 'false'"
                aria-labelledby="toggleLabel"
                @if($attributes->whereStartsWith('wire:model')->first())
                    x-data="{ on: @entangle($attributes->wire('model')) }"
                @else
                    x-data="{ on: {{ $value ? 'true' : 'false' }} }"
                @endif
                :class="{ 'bg-gray-200': !on, 'bg-primary-500': on }"
                @class(["relative inline-flex flex-shrink-0 h-5 w-9 border-2 border-transparent rounded-sm cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none bg-gray-200", $innerClass])
                {{ $attributes->whereStartsWith(['wire:change', 'x-']) }}
        >
            <span class="sr-only">{{ $label }}</span>
            <span aria-hidden="true"
                  :class="{ 'translate-x-4': on, 'translate-x-0': !on }"
                  class="inline-block h-4 w-4 rounded-sm bg-white shadow transform ring-0 transition ease-in-out duration-200 translate-x-0"
            ></span>
        </button>
        <span id="toggleLabel">
            <span class="block text-sm text-gray-600">{{ $label }}</span>
            @isset($help)
                <span class="mt-1 text-xs text-gray-500">{{ $help }}</span>
            @endisset
        </span>
    </div>
    @error($id)
    <p class="mt-1 text-xs text-red-600" id="{{ $id }}-error">{{ \Illuminate\Support\Str::of($message)->replace($id, '') }}</p>
    @enderror
</fieldset>
