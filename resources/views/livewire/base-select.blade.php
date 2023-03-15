<div class="flex flex-1 w-full items-center">
    <input type="hidden" name="{{ $this->name }}" value="{{ $this->selectedValue }}">

    @isset($this->titleKey)
        <input type="hidden" name="{{ $this->titleKey }}" value="{{ $this->titleValue }}">
    @endisset

    <x-wireforms::select />
</div>
