<input type="hidden"
       name="{{ $name }}"
       id="{{ $id }}"
       @isset($value)
           value="{{ is_array($value) ? json_encode($value) : $value }}"
       @endisset
       {{ $attributes->whereStartsWith(['data', 'wire:model', 'wire:change', 'x-']) }}
>
