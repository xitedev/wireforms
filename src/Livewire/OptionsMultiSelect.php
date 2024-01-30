<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Xite\Searchable\Filters\SearchFilter;

class OptionsMultiSelect extends BaseSelect
{
    public ?array $options = [];
    public ?array $values = [];

    public function mount(
        string $name,
        string $placeholder = null,
        string $value = null,
        bool $required = false,
        bool $readonly = false,
        ?int $minInputLength = null,
        ?int $limit = null,
        bool $searchable = false,
        ?string $viewName = null,
        ?string $emitUp = null,
        ?array $options = [],
        ?array $values = [],
    ): void {
        $this->name = $name;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->readonly = $readonly;
        $this->minInputLength = $minInputLength;
        $this->limit = $limit;
        $this->searchable = $searchable;
        $this->emitUp = $emitUp;
        $this->viewName = $viewName;
        $this->options = $options;
        $this->values = $values;
    }

    protected function getListeners(): array
    {
        return [
            'fillParent.' . $this->getId() => 'setSelected'
        ];
    }

    public function getSelectedValueProperty(): ?string
    {
        return implode(',', $this->values ?? []);
    }

    public function getSelectedValuesProperty(): array
    {
        return array_intersect_key($this->options, array_flip($this->values ?? []));
    }

    #[Computed]
    public function getResults(): Collection
    {
        return collect($this->options);
    }

    #[On('setSelected')]
    public function setSelected($value, ?bool $trigger = true): void
    {
        if (in_array($value, $this->values, true)) {
            $this->values = array_diff($this->values, [$value]);
        } else {
            $this->values[] = $value;
        }

        if ($trigger) {
            $this->dispatch(
                event: $this->emitUp,
                key: $this->name,
                value: implode(',', $this->values)
            );
        }
    }

    public function isCurrent(string $key): bool
    {
        return in_array($key, $this->values);
    }

    public function render(): View
    {
        return view($this->viewName ?? 'wireforms::livewire.options-multi-select');
    }
}
