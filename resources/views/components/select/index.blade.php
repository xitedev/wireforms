@props([
     'listEmpty',
     'currentItem',
     'listItems'
])

<div class="relative flex-1 w-full" x-data="{ open: @entangle('isOpen') }">
    <button type="button"
            aria-haspopup="listbox"
            :aria-expanded="open ? 'true' : 'false'"
            aria-labelledby="listbox-label"
            @if(!$this->readonly)
                x-on:click.prevent="open = !open"
            @endif
            @class([
                'relative flex items-center min-h-[34px] w-full border px-3 text-left cursor-pointer transition ease-in-out duration-150 disabled:bg-gray-200 rounded-sm',
                'bg-gray-50 cursor-not-allowed' => $this->readonly,
                'bg-white cursor-pointer focus:outline-none' => !$this->readonly,
                'border-primary-300' => $this->isOpen,
                'border-gray-200 focus:border-primary-300' => !$this->isOpen
            ])
    >
        @isset($currentItem)
            {{ $currentItem }}
        @else
            <div class="animate-pulse space-y-1 grid grid-cols-4 w-1/2"
                 wire:loading.grid
                 wire:target="setSelected"
                 style="display: none;"
            >
                <div class="h-2 bg-gray-200 rounded col-span-3"></div>

                <div class="grid grid-cols-3 gap-3 col-span-4">
                    <div class="h-1 bg-gray-200 rounded col-span-2"></div>
                    <div class="h-1 bg-gray-200 rounded col-span-1"></div>
                </div>
            </div>

            <div class="flex justify-between items-center w-full transition-opacity"
                 wire:loading.remove
                 wire:target="setSelected"
            >
                @if($this->selectedValue)
                    <div class="flex-1 truncate w-full text-gray-700">
                        {{ $this->selectedTitle }}
                    </div>

                    @if(!$this->readonly)
                        <span class="cursor-pointer" wire:click.prevent="setSelected(null)">
                            <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    @endif
                @else
                    <div class="flex-1 truncate text-gray-400">
                        {{ ($this->placeholder ?? __('wireforms::form.please_select')) }}
                    </div>

                    <svg class="h-5 w-5 text-gray-400 pointer-events-none" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                        <path d="M7 7l3-3 3 3m0 6l-3 3-3-3" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                @endif
            </div>
        @endisset
    </button>

    @if(!$this->readonly)
        <div @class([
            "absolute w-full bg-white border border-primary-300 border-t-0 rounded-b-sm -mt-px z-20 transition ease-in-out duration-150",
            'min-h-[80px]' => $this->searchable,
            ])
             @if($this->searchable)
                 x-effect="$refs.search.focus()"
             @endif
             x-show="open"
             x-trap="open"
             x-on:click.away="open = false"
             x-cloak
        >
            @if($this->searchable)
                <div class="flex items-center justify-between m-1 pl-1 pr-2 border border-primary-100 shadow-sm shadow-primary-100/50 bg-primary-50/50">
                    <input id="search"
                           type="text"
                           name="search"
                           x-ref="search"
                           class="block flex-1 p-1 text-sm text-gray-700 bg-primary-50/25 outline-none"
                           wire:model.live.debounce.1s="search"
                           placeholder="@lang('wireforms::form.search')"
                           autocomplete="false"
                           autofocus
                    >
                    <div class="flex items-center pointer-events-none"
                         wire:loading
                         wire:target="search"
                         style="display: none;"
                    >
                        <svg class="h-4 w-4 text-gray-400 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </div>
                </div>
            @endif

            <span class="py-2 text-gray-300 w-full block text-center text-base leading-6 sm:text-sm sm:leading-5"
                  wire:loading
                  style="display: none;"
            >
                @lang('wireforms::form.loading')
            </span>

            <ul tabindex="-1"
                role="listbox"
                aria-labelledby="listbox-label"
                aria-activedescendant="listbox-options"
                class="max-h-64 w-full text-base leading-6 overflow-auto focus:outline-none sm:text-sm sm:leading-5"
                wire:loading.class="hidden"
            >
                @if($this->minInputLength && $this->minInputLength > Str::of($this->search)->trim()->length())
                    <x-wireforms::select.list-item
                        key="0-min-input-length"
                        :value="__('wireforms::form.min_input_length', ['count' => $this->minInputLength])"
                        class="text-gray-500 text-center cursor-default select-none"
                    />
                @else
                    @isset($listItems)
                        {{ $listItems }}
                    @else
                        @forelse($this->getResults as $key => $value)
                            <x-wireforms::select.list-item
                                :key="$key"
                                :value="$value"
                                class="text-gray-900 focus:text-white focus:bg-primary-600 hover:text-white hover:bg-primary-600 cursor-pointer select-none pr-9"
                                x-on:click.prevent="open = !open; $wire.setSelected('{{ addslashes($key) }}')"
                            />
                        @empty
                            @isset($listEmpty)
                                {{ $listEmpty }}
                            @else
                                <x-wireforms::select.list-item
                                    key="0-null"
                                    :value="__('wireforms::form.not_found')"
                                    class="text-gray-500 text-center cursor-default select-none"
                                />
                            @endisset
                        @endforelse
                    @endisset
                @endif
            </ul>
        </div>
    @endif
</div>
