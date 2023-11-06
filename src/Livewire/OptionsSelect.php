<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Xite\Searchable\Filters\SearchFilter;

class OptionsSelect extends BaseSelect
{
    public ?array $options = [];

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
        ?bool $multiple = false,
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
        $this->multiple = $multiple;
    }

    protected function getListeners(): array
    {
        return [
            'fillParent' => 'setSelected',
            'fillParent.' . $this->id => 'setSelected'
        ];
    }

    public function getSelectedValueProperty(): ?string
    {
        return $this->value;
    }

    public function getSelectedTitleProperty(): ?string
    {
        return $this->options[$this->value] ?? null;
    }

    public function getResultsProperty(): Collection
    {
        if (! $this->isOpen) {
            return collect();
        }

        return collect($this->options);
    }

    public function setSelected($value, ?bool $trigger = true): void
    {
        $this->isOpen = false;

        if ($this->value === $value) {
            return;
        }

        $this->value = $value;

        if ($trigger) {
            $this->emitUp($this->emitUp, $this->name, $this->value);
        }
    }

    public function isCurrent(string $key): bool
    {
        return $key === $this->value;
    }

    public function render(): View
    {
        return view($this->viewName ?? 'wireforms::livewire.options-select');
    }
}
