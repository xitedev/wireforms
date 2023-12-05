<div class="flex flex-1 w-full items-center">
    <input type="hidden" name="{{ $this->name }}" value="{{ $this->selectedValue }}">

    @isset($this->titleKey)
        <input type="hidden" name="{{ $this->titleKey }}" value="{{ $this->titleValue }}">
    @endisset

    <x-wireforms::select>
        <x-slot:listItems>
            @forelse($this->results as $key => $value)
                @if($value['childrenCount'] > 0)
                    <x-wireforms::select.list-item
                        key="group-{{ $key }}"
                        :value="$value['name']"
                        class="uppercase text-xs text-gray-500 cursor-default select-none"
                    />

                    @foreach($value['children'] as $childKey => $childValue)
                        <x-wireforms::select.list-item
                            :key="$childKey"
                            :value="$childValue"
                            class="text-gray-900 focus:text-white focus:bg-primary-600 hover:text-white hover:bg-primary-600 cursor-pointer select-none pl-5 pr-9"
                            x-on:click.prevent="open = !open; $wire.setSelected('{{ $childKey }}')"
                        />
                    @endforeach
                @else
                    <x-wireforms::select.list-item
                        :key="$key"
                        :value="$value['name']"
                        class="text-gray-900 focus:text-white focus:bg-primary-600 hover:text-white hover:bg-primary-600 cursor-pointer select-none pr-9"
                        x-on:click.prevent="open = !open; $wire.setSelected('{{ $key }}')"
                    />
                @endif
            @empty
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
            @endforelse
        </x-slot:listItems>
    </x-wireforms::select>
</div>
