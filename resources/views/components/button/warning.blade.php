@props([
    'outline' => false,
    'icon' => null,
    'title' => null
])

<x-wireforms::button
    {{ $attributes->class([
	    $outline
	    ? 'text-yellow-500 bg-transparent hover:bg-yellow-600 active:bg-yellow-600 border-yellow-500'
	    : 'text-white bg-yellow-500 hover:bg-yellow-600 active:bg-yellow-600 border-yellow-500',
	  'border hover:text-white'
	  ])->merge() }}
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
