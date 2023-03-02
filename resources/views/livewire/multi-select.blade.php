<div class="flex flex-1 w-full items-center">
    <input type="hidden" name="{{ $name }}" value="{{ $this->selectedValue }}">

    <div class="relative flex-1 w-full" x-data="{ open: @entangle('isOpen'), readonly: @entangle('readonly'), searchable: @entangle('searchable') }">
        <button type="button"
                aria-haspopup="listbox"
                :aria-expanded="open ? 'true' : 'false'"
                aria-labelledby="listbox-label"
                x-on:click.prevent="open = (readonly) ? false : !open"
                class="relative w-full flex flex-wrap gap-2 border pl-3 pr-10 py-1.5 text-left cursor-pointer transition ease-in-out duration-150 sm:text-sm sm:leading-5 disabled:bg-gray-200 rounded-sm"
                :class="{'bg-gray-50 cursor-not-allowed': readonly, 'bg-white cursor-pointer focus:outline-none focus:shadow-full focus:shadow-primary-100/50': !readonly, 'border-primary-300 border-b-white': open, 'border-gray-200 focus:border-primary-300': !open }"
        >
            @forelse($this->selectedModel as $selected)
                <div class="border border-gray-400 bg-gray-100 flex items-center space-x-1 px-1 rounded text-xs" wire:key="value-{{ $selected->getKey() }}">
                    <span class="truncate text-gray-500 max-w-64">{{ $selected->getDisplayName() }}</span>

                    @if(!$readonly)
                        <span class="cursor-pointer" x-on:click.stop="$wire.call('setSelected', {{ $selected->getKey() }})">
                            <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    @endif
                </div>
            @empty
                <span class="block truncate text-gray-400">{{ $placeholder ?? __('wireforms::form.please_select') }}</span>

                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                        <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            @endforelse
        </button>
        @if($isOpen)
            <div class="absolute w-full bg-white border border-primary-300 border-t-0 -mt-px z-20"
                 x-show="!readonly"
                 x-effect="searchable === true && $refs.search.focus()"
                 x-on:click.away="open = false"
            >
                <div class="relative p-1" x-show="searchable">
                    <label class="sr-only" for="search">@lang('wireforms::form.search')</label>
                    <input id="search"
                           type="text"
                           name="search"
                           x-ref="search"
                           class="block w-full p-1 border border-primary-100 text-sm text-gray-700 bg-primary-50/50 shadow-sm shadow-primary-100/50 outline-none"
                           wire:model.debounce.1s="search"
                           placeholder="@lang('wireforms::form.search')"
                           autocomplete="false"
                    >
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <svg wire:loading wire:target="search" class="h-4 w-4 text-gray-400 animate" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
                @if($this->showResults())
                    <ul tabindex="-1"
                        role="listbox"
                        aria-labelledby="listbox-label"
                        aria-activedescendant="listbox-option-{{ $this->value }}"
                        class="max-h-60 pt-1 text-base leading-6 overflow-auto focus:outline-none sm:text-sm sm:leading-5"
                    >
                        @forelse($this->results as $key => $value)
                            <li id="listbox-option-{{ $key }}"
                                role="option"
                                class="group text-gray-900 focus:text-white focus:bg-primary-600 hover:text-white hover:bg-primary-600 cursor-pointer select-none relative py-2 pl-3 pr-9"
                                wire:key="listbox-option-{{ $key }}"
                                x-on:click.prevent="$wire.call('setSelected', '{{ $key }}')"
                            >
                                <span @class(['block truncate', 'font-semibold' => $this->isCurrent($key)])>
                                    {{ $value }}
                                </span>
                                @if($this->isCurrent($key))
                                    <span class="text-primary-600 group-focus:text-white group-hover:text-white absolute inset-y-0 right-0 flex items-center pr-4">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                @endif
                            </li>
                        @empty
                            @if($createNewModel)
                                <li id="listbox-option-new"
                                    role="option"
                                    class="group text-gray-900 focus:text-white focus:bg-primary-600 hover:text-white hover:bg-primary-600 cursor-pointer select-none relative py-2 pl-3 pr-9"
                                    wire:key="listbox-option-new"
                                    wire:click.prevent="$emit('openModal', '{{ $createNewModel }}', {{ $this->createNewParams }})"
                                >
                                    <span class="block truncate">
                                        @lang('wireforms::form.empty_result_create_new')
                                    </span>
                                </li>
                            @else
                                <li id="listbox-option-null" role="option" class="group text-gray-500 cursor-default select-none relative py-2 pl-3 pr-9">
                                <span class="font-normal block truncate">
                                    @lang('wireforms::form.not_found')
                                </span>
                                </li>
                            @endif
                        @endforelse
                    </ul>
                @elseif($this->minInputLength)
                    <span class="py-2 text-gray-300 w-full block text-center text-base leading-6 sm:text-sm sm:leading-5">
                        @lang('wireforms::form.min_input_length', ['count' => $this->minInputLength])
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>
