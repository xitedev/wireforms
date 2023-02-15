<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Xite\Searchable\Filters\SearchFilter;

class NestedSetSelect extends ModelSelect
{
    public ?string $viewName = 'wireforms::livewire.nested-set-select';

    protected function selected()
    {
        if (is_null($this->value)) {
            return null;
        }

        return once(
            fn () => $this->searchQuery()
                ->with('ancestors')
                ->find($this->value)
        );
    }

    public function getSelectedValueProperty(): ?string
    {
        return $this->selected()?->getKey();
    }

    public function getSelectedTitleProperty(): ?string
    {
        if (is_null($this->selected())) {
            return null;
        }

        return $this->selected()
            ->ancestors
            ->push($this->selected())
            ->implode('name', ' > ');
    }

    public function getModelKeyNameProperty(): ?string
    {
        return (new $this->model())->getKeyName();
    }

    private function searchQuery(): Builder
    {
        if (method_exists($this->model, 'searchQuery')) {
            return $this->model::searchQuery();
        }

        return $this->model::query();
    }

    public function getResultsProperty(): Collection
    {
        if (! $this->isOpen || ! $this->showResults()) {
            return collect();
        }

        return $this->searchQuery()
            ->whereNull('parent_id')
            ->when(
                $this->searchable && $this->search,
                fn ($query) => $query->where(
                    fn ($query) => $query
                        ->tap(new SearchFilter($this->search))
                        ->orWhereHas(
                            'children',
                            fn ($query) => $query->tap(new SearchFilter($this->search))
                        )
                )
            )
            ->with([
                'children' => fn ($query) => $query->tap(new SearchFilter($this->search)),
            ])
            ->withCount('children')
            ->orderBy(
                $this->orderBy ?? $this->getModelKeyNameProperty(),
                $this->orderDir
            )
            ->take($this->limit)
            ->get()
            ->mapWithKeys(fn ($item) => [
                $item->getKey() => [
                    'name' => $item->getDisplayName(),
                    'children' => $item->children->mapWithKeys(fn ($child) => [
                        $child->getKey() => $child->getDisplayName(),
                    ]),
                    'childrenCount' => $item->children_count,
                ],
            ]);
    }

    public function isCurrent(string $key): bool
    {
        return $this->selected() && $key === $this->selectedValue;
    }
}
