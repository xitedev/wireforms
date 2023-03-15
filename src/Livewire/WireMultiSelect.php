<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Xite\Searchable\Filters\SearchFilter;

class WireMultiSelect extends ModelSelect
{
    public ?string $model = null;
    public ?string $createNewModel = null;
    public ?string $createNewField = null;
    public ?Collection $filters = null;
    public ?array $values = [];

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
        ?array $values = [],
    ): void {
        $this->name = $name;
        $this->value = $value;
        $this->required = $required;
        $this->placeholder = $placeholder;
        $this->readonly = $readonly;
        $this->minInputLength = $minInputLength;
        $this->limit = $limit;
        $this->searchable = $searchable;
        $this->orderBy = $orderBy;
        $this->orderDir = $orderDir;
        $this->model = $model;
        $this->createNewModel = $createNewModel;
        $this->createNewField = $createNewField;
        $this->editModel = $editModel;
        $this->filters = $filters;
        $this->values = $values;

        if ($viewName) {
            $this->viewName = $viewName;
        }
    }

    protected function selected()
    {
        return once(
            fn () => $this->searchQuery()->findMany($this->values)
        );
    }

    public function getSelectedModelProperty()
    {
        return $this->selected();
    }

    public function getSelectedValueProperty(): ?string
    {
        return $this->selected()?->pluck($this->modelKeyName)->implode(',');
    }

    public function getModelKeyNameProperty(): ?string
    {
        return (new $this->model())->getKeyName();
    }

    public function setSelected($value, ?bool $trigger = true): void
    {
        if (in_array($value, $this->values, true)) {
            $this->values = array_diff($this->values, [$value]);
        } else {
            $this->values[] = $value;
        }

        if ($trigger) {
            $this->emitUp($this->emitUp, $this->name, $this->values);
        }
    }

    private function searchQuery(): Builder
    {
        $query = method_exists($this->model, 'searchQuery')
            ? $this->model::searchQuery()
            : $this->model::query();

        if ($this->filters?->count()) {
            $this->filters->each(
                fn ($value, $key) => $query->when(
                    ($string = Str::of($key)) && $string->startsWith('scope'),
                    fn (Builder $query) => $query->{$string->replaceFirst('scope', '')->camel()->toString()}($value),
                    fn ($query) => $query->when(
                        is_array($value),
                        fn ($query) => $query->whereIn($key, $value),
                        fn ($query) => $query->where($key, $value)
                    )
                )
            );
        }

        return $query;
    }

    public function getResultsProperty(): Collection
    {
        if (! $this->isOpen) {
            return collect();
        }

        return $this->searchQuery()
            ->when(
                $this->searchable,
                new SearchFilter($this->search)
            )
            ->orderBy(
                $this->orderBy ?? $this->modelKeyName,
                $this->orderDir
            )
            ->take($this->limit)
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item->getKey() => $item->getDisplayName(),
            ]);
    }

    public function isCurrent(string $key): bool
    {
        return $this->selected() && $this->selected()->contains($this->modelKeyName, $key);
    }

    public function render(): View
    {
        return view($this->viewName ?? 'wireforms::livewire.multi-select');
    }
}
