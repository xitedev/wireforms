@props([
    'outline' => false,
    'icon' => null,
    'title' => null
])

<x-wireforms::button
    {{ $attributes->class([
        $outline
        ? 'text-emerald-500 bg-transparent hover:bg-emerald-500 active:bg-emerald-500 border-emerald-500'
        : 'text-white bg-emerald-500 hover:bg-emerald-600 active:bg-emerald-600 border-emerald-500',
        'border hover:text-white'
    ]) }}
>
    @if($icon)
        @svg($icon, 'w-4 h-4')
    @endif
    @if($title)
        <span>{{ $title }}</span>
    @endif
    @isset($slot)
        {{ $slot }}
    @endisset
</x-wireforms::button>
