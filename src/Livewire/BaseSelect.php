<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

abstract class BaseSelect extends Component
{
    public string $search = '';
    public bool $required = false;

    public string $name;
    public ?string $placeholder = null;
    public ?string $value = null;
    public bool $isOpen = false;
    public bool $readonly = false;
    public bool $searchable = true;
    public int $limit = 20;
    public ?int $minInputLength = null;
    public ?string $viewName;
    public ?string $emitUp = 'updatedChild';

    public function mount(
        string $name,
        string $placeholder = null,
        string $value = null,
        bool $required = false,
        bool $readonly = false,
        ?int $minInputLength = null,
        ?int $limit = null,
        bool $searchable = true,
        ?string $viewName = null,
        ?string $emitUp = null
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
    }

    abstract public function getResultsProperty(): ?Collection;

    abstract public function isCurrent(string $key): bool;

    public function render(): View
    {
        return view($this->viewName ?? 'wireforms::livewire.base-select');
    }
}
