<x-wireforms::fields
    :name="$name"
    :id="$id"
    :required="$required"
    :label="$label"
    :show-label="$showLabel"
    :help="$help"
    :key="$key"
    {{ $attributes->whereDoesntStartWith(['min', 'max', 'step', 'data', 'x-', 'wire:model']) }}
>
    <div class="relative flex w-full" x-data="{ value: null }" x-modelable="value" {{ $attributes->whereStartsWith('wire:model') }}>
        <button class="inline-flex items-center space-x-1 w-auto py-1.5 px-3.5 leading-5 font-medium rounded-sm focus:outline-none transition duration-150 ease-in-out disabled:cursor-wait disabled:opacity-75 text-white bg-gray-500 hover:bg-gray-600 active:bg-gray-600 border-gray-500 border hover:text-white !rounded-r-none" @click.prevent="() => { if (!$refs.input.min || value > $refs.input.min) { value-- } }">
            -
        </button>

        <input type="number"
               name="{{ $name }}"
               id="{{ $id }}"
               x-ref="input"
               x-model.number="value"
               @isset($value)
                   value="{{ $value }}"
               @endisset
               @if($placeholder)
                   placeholder="{{ $placeholder }}"
               @endif
               @class([
                'block w-full px-3 py-1.5 border duration-300 transition-all sm:text-sm focus:outline-none focus:shadow-full rounded-sm disabled:bg-gray-200 no-spinners',
                'border-gray-200 text-gray-700 placeholder-gray-400 focus:ring-primary-300 focus:border-primary-300 focus:shadow-primary-100/50' => !$errors->has($id),
                'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-300 focus:border-red-300 focus:shadow-red-100/75' => $errors->has($id),
                $innerClass
               ])
               @required($required)
               @disabled($disabled)
               {{ $attributes->whereStartsWith(['min', 'max', 'step', 'data', 'x-']) }}
        >

        <button class="inline-flex items-center space-x-1 w-auto py-1.5 px-3.5 leading-5 font-medium rounded-sm focus:outline-none transition duration-150 ease-in-out disabled:cursor-wait disabled:opacity-75 text-white bg-gray-500 hover:bg-gray-600 active:bg-gray-600 border-gray-500 border hover:text-white !rounded-l-none" @click.prevent="() => { if (!$refs.input.max || value < $refs.input.max) { value++ } }">
            +
        </button>

        @isset($slot)
            {{ $slot }}
        @endisset
    </div>
</x-wireforms::fields>
