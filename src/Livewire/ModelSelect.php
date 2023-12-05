<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\On;

abstract class ModelSelect extends BaseSelect
{
    public ?string $model = null;
    public ?string $orderBy = 'id';
    public ?string $orderDir = 'asc';
    public ?string $createNewModel = null;
    public ?string $createNewField = null;
    public ?string $editModel = null;
    public ?Collection $filters = null;
    public ?array $fillFields = [];

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
        ?string $model = null,
        ?string $orderBy = null,
        ?string $orderDir = null,
        ?string $createNewModel = null,
        ?string $createNewField = null,
        ?string $editModel = null,
        ?Collection $filters = null,
        ?array $fillFields = []
    ): void {
        $this->name = $name;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->readonly = $readonly;
        $this->minInputLength = $minInputLength;
        $this->limit = $limit;
        $this->searchable = $searchable;
        $this->model = $model;
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
        $this->createNewModel = $createNewModel;
        $this->createNewField = $createNewField;
        $this->editModel = $editModel;
        $this->filters = $filters;
        $this->viewName = $viewName;
        $this->fillFields = $fillFields;
    }

    protected function getListeners(): array
    {
        return [
            'fillParent.' . $this->getId() => 'setSelected'
        ];
    }

    #[On('setSelected')]
    public function setSelected($value, ?bool $trigger = true): void
    {
        $this->search = '';
        $this->isOpen = false;

        if ($this->value === $value) {
            return;
        }

        $this->value = $value;

        if ($trigger) {
            $this->dispatchTo($this->emitUp, $this->name, $this->value);
        }
    }

    #[On('changeFilter')]
    public function changeFilter(string $filter, $value): void
    {
        $this->filters = $this->filters->put($filter, $value);
    }

    public function getCreateNewParamsProperty(): string
    {
        return collect()
            ->when(
                $this->createNewField,
                fn ($collection) => $collection
                    ->when(
                        $this->search,
                        fn ($collection) => $collection->put('fillFields', array_merge(
                                [$this->createNewField => $this->search],
                                $this->fillFields
                            )
                        )
                            ->put('parentModal', $this->getId())
                    )
            )
            ->toJson();
    }
}
