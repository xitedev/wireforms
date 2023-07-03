<?php

namespace Xite\Wireforms\Livewire;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Xite\Searchable\Filters\SearchFilter;

class WireSelect extends ModelSelect
{
    protected function selected()
    {
        if (is_null($this->value)) {
            return null;
        }

        return once(
            fn () => $this->searchQuery()->find($this->value)
        );
    }

    public function getSelectedModelProperty()
    {
        return $this->selected();
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

        return $this->selected()?->getDisplayName();
    }

    public function getModelKeyNameProperty(): ?string
    {
        return (new $this->model())->getKeyName();
    }

    private function searchQuery(): Builder
    {
        $query = method_exists($this->model, 'searchQuery')
            ? $this->model::searchQuery()
            : $this->model::query();

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
            ->when(
                $this->filters?->count(),
                fn ($query) => $query->tap(function ($query) {
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
                })
            )
            ->orderBy(
                $this->orderBy ?? $this->getModelKeyNameProperty(),
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
        return $this->selected() && $key === $this->selectedValue;
    }

    public function render(): View
    {
        return view($this->viewName ?? 'wireforms::livewire.model-select');
    }
}
