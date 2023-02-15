<button
    {{ $attributes->merge([
        'type' => 'button'
    ])->class([
        'inline-flex items-center space-x-1 w-auto py-1.5 px-3.5 leading-5 font-medium rounded-sm focus:outline-none transition duration-150 ease-in-out disabled:cursor-wait disabled:opacity-75',
        'opacity-75 cursor-not-allowed' => $attributes->get('disabled')
    ]) }}
>
    {{ $slot }}
</button>
