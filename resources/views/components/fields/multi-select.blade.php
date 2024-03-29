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
    <div class="flex items-center"
        {{ $attributes->whereStartsWith(['x-']) }}
    >
        <livewire:wireforms.livewire.options-multi-select
            :name="$id"
            :required="$required"
            :placeholder="$placeholder"
            :readonly="$readonly"
            :limit="$limit"
            :min-input-length="$minInputLength"
            :nullable="$nullable"
            :values="$value"
            :emit-up="$emitUp"
            :key="$key ?? $id"
            :options="$options"
            :searchable="$searchable"
        />
    </div>
</x-wireforms::fields>
