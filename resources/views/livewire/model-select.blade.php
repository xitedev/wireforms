<div class="flex flex-1 w-full items-center">
    <input type="hidden" name="{{ $this->name }}" value="{{ $this->selectedValue }}">

    @isset($this->titleKey)
        <input type="hidden" name="{{ $this->titleKey }}" value="{{ $this->titleValue }}">
    @endisset

    <x-wireforms::select>
        <x-slot:listEmpty>
            @if($createNewModel)
                <x-wireforms::select.list-item
                    key="listbox-option-0-new"
                    :value="__('wireforms::form.empty_result_create_new')"
                    class="text-gray-900 focus:text-white focus:bg-primary-600 hover:text-white hover:bg-primary-600 cursor-pointer select-none"
                    wire:click.prevent="$dispatch('openModal', { component: '{{ $createNewModel }}', arguments: {{ $this->createNewParams }}})"
                />
            @else
                <x-wireforms::select.list-item
                    key="0-null"
                    :value="__('wireforms::form.not_found')"
                    class="text-gray-500 text-center cursor-default select-none"
                />
            @endif
        </x-slot:listEmpty>
    </x-wireforms::select>

    @if(isset($editModel) && $this->selectedValue)
        <button type="button"
                class="ml-2 p-2 group flex items-center text-sm justify-center space-x-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"
                wire:click.prevent="$dispatch('openModal', { component: '{{ $editModel }}', arguments: {{ json_encode(['model' => $this->selectedValue], JSON_THROW_ON_ERROR) }}})"
        >
            <svg class="h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
            </svg>
        </button>
    @endif
</div>
