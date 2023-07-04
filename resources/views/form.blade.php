<form class="border border-primary-300" wire:submit.prevent="save">
    <div class="bg-primary-500 text-white flex justify-between items-center px-4 py-3">
        <h4 class="text-base font-semibold tracking-wide">{{ $title }}</h4>

        <button type="button" class="text-white hover:text-gray-200 text-xl font-bold" wire:click="$emit('closeModal')">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <div class="p-3">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            @foreach($fields as $field)
                {!! $field !!}
            @endforeach
        </div>
    </div>

    <div class="p-3 flex justify-end space-x-2 border-t border-gray-100">
        <x-wireforms::button.secondary
            wire:click="$emit('closeModal')"
            :title="__('wireforms::form.close')"
        />

        <x-wireforms::button.primary
            type="submit"
            :title="__('wireforms::form.save')"
            wire:loading.attr="disabled"
        />
    </div>
</form>
