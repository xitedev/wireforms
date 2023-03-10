<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class ModelSelect extends BaseSelect
{
    public ?string $model = null;
    public ?string $orderBy = 'id';
    public ?string $orderDir = 'asc';
    public ?string $createNewModel = null;
    public ?string $createNewField = null;
    public ?string $editModel = null;
    public ?Collection $filters = null;

    public function mount(
        string $name,
        string $placeholder = null,
        string $value = null,
        bool $required = false,
        bool $readonly = false,
        ?int $minInputLength = null,
        ?int $limit = 20,
        bool $searchable = true,
        ?string $viewName = null,
        string $model = null,
        ?string $orderBy = null,
        ?string $orderDir = null,
        string $createNewModel = null,
        string $createNewField = null,
        string $editModel = null,
        ?Collection $filters = null
    ): void {
        $this->name = $name;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->readonly = $readonly;
        $this->minInputLength = $minInputLength;
        $this->limit = $limit;
        $this->searchable = $searchable;
        $this->viewName = $viewName;
        $this->model = $model;
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
        $this->createNewModel = $createNewModel;
        $this->createNewField = $createNewField;
        $this->editModel = $editModel;
        $this->filters = $filters;
    }

    protected function getListeners(): array
    {
        return [
            'fillParent' => 'setSelected',
            'fillParent.' . $this->id => 'setSelected',
            'changeFilter' => 'changeFilter',
        ];
    }

    public function setSelected($value, ?bool $trigger = true): void
    {
        $this->search = '';
        $this->isOpen = false;

        if ($this->value === $value) {
            return;
        }

        $this->value = $value;

        if ($trigger) {
            $this->emitUp($this->emitUp, $this->name, $this->value);
        }
    }

    public function changeFilter(string $filter, $value): void
    {
        $this->filters = $this->filters->put($filter, $value);
    }

    public function showResults(): bool
    {
        return is_null($this->minInputLength) || $this->minInputLength <= Str::length($this->search);
    }

    public function getCreateNewParamsProperty(): string
    {
        return collect()
            ->when(
                $this->createNewField,
                fn ($collection) => $collection
                    ->when(
                        $this->search,
                        fn ($collection) => $collection->put('fillFields', [
                            $this->createNewField => $this->search,
                        ])
                    )
                    ->put('parentModal', $this->id)
            )
            ->toJson();
    }
}
