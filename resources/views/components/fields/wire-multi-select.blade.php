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
    <div class="flex items-center"
        {{ $attributes->whereStartsWith(['x-']) }}
    >
        <livewire:wireforms.livewire.wire-multi-select
            :name="$id"
            :model="$model"
            :create-new-model="$createNewModel"
            :create-new-field="$createNewField"
            :required="$required"
            :placeholder="$placeholder"
            :readonly="$readonly"
            :searchable="$searchable"
            :min-input-length="$minInputLength"
            :nullable="$nullable"
            :order-by="$orderBy"
            :order-dir="$orderDir"
            :values="$value"
            :filters="$filters"
            :emit-up="$emitUp"
            :view-name="$customView"
            :fill-fields="$fillFields"
            :key="$key ?? $id"
        />
    </div>
</x-wireforms::fields>
