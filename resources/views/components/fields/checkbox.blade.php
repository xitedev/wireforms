<fieldset
    @if($key) wire:key="{{ $key }}" @endif
    {{ $attributes->class('space-y-1')->only('class') }}
>
    <div class="flex items-center space-x-2">
        <input type="checkbox"
               @class(["appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-primary-500 checked:border-primary-500 focus:outline-none transition duration-200 my-1 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer", $innerClass])
               {{ $attributes->whereStartsWith('wire:model') }}
               name="{{ $name }}"
               value="{{ $value }}"
               id="{{ $name }}"
            @disabled($disabled)
        >

        @if($label)
            <span id="toggleLabel">
                <span class="block text-sm text-gray-600">{{ $label }}</span>
            </span>
        @endif
    </div>

    @isset($help)
        <span class="mt-1 text-xs text-gray-500">{{ $help }}</span>
    @endisset

    @error($id)
        <p class="mt-1 text-xs text-red-600" id="{{ $id }}-error">
            {{ \Illuminate\Support\Str::of($message)->replace($id, '') }}
        </p>
    @enderror
</fieldset>
