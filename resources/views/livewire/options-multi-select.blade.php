<div class="flex flex-1 w-full items-center">
    <input type="hidden" name="{{ $name }}" value="{{ $this->selectedValue }}">

    <div class="flex flex-1 w-full items-center">
        <x-wireforms::select>
            <x-slot:currentItem>
                <div class="flex flex-wrap gap-2 py-1">
                    @forelse($this->selectedValues as $key => $selected)
                        <div class="border border-primary-400 bg-primary-50 flex items-center space-x-1 px-1 rounded-sm text-sm" wire:key="{{ $this->getId() }}-value-{{ $key }}">
                            <span class="truncate text-gray-500 max-w-64">{{ $selected }}</span>

                            @if(!$this->readonly)
                                <span class="cursor-pointer" x-on:click.stop="$wire.setSelected('{{ $key }}')">
                                    <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                    @empty
                        <span class="block truncate text-gray-400">{{ $this->placeholder ?? __('wireforms::form.please_select') }}</span>

                        <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                    @endforelse
                </div>
            </x-slot:currentItem>
        </x-wireforms::select>
    </div>
</div>
