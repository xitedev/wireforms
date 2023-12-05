<?php

namespace Xite\Wireforms\FormFields;

use Illuminate\Support\Collection;
use Livewire\Component;
use Xite\Wireforms\Components\Fields\WireMultiSelect;
use Xite\Wireforms\Contracts\FieldContract;
use Xite\Wireforms\Traits\Authorizable;

class WireMultiSelectField extends FormField
{
    use Authorizable;

    protected bool $nullable = false;
    protected ?string $model = null;
    protected bool $searchable = false;
    protected string $orderDir = 'asc';
    protected ?string $orderBy = null;
    protected bool $nestedSet = false;
    protected ?string $createNewModel = null;
    protected ?string $createNewField = null;
    protected ?string $editModel = null;
    protected ?int $minInputLength = null;
    protected ?string $customView = null;
    protected ?Collection $filters = null;
    protected ?array $fillFields = [];

    public function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function createNewModel(string $modelComponent, ?string $field = null): self
    {
        $component = app($modelComponent);

        if ($component instanceof Component && $this->model && $this->authorizeModel('create', $this->model)) {
            $this->createNewModel = $component::getComponentName();
            $this->createNewField = $field;
        }

        return $this;
    }

    public function minInputLength(int $minInputLength): self
    {
        $this->minInputLength = $minInputLength;

        return $this;
    }

    public function customView(string $customView): self
    {
        $this->customView = $customView;

        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;
        $this->rules[] = 'nullable';

        return $this;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function orderDir(string $orderDir): self
    {
        $this->orderDir = $orderDir;

        return $this;
    }

    public function orderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function fillFields(array $fillFields): self
    {
        $this->fillFields = $fillFields;

        return $this;
    }

    public function filterBy(string $key, $value): self
    {
        if (is_null($value)) {
            return $this;
        }

        if (! $this->filters) {
            $this->filters = Collection::make();
        }

        $this->filters->put($key, $value);

        return $this;
    }

    protected function render(): FieldContract
    {
        return WireMultiSelect::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            model: $this->model,
            nullable: $this->nullable,
            searchable: $this->searchable,
            orderBy: $this->orderBy,
            orderDir: $this->orderDir,
            label: $this->label,
            help: $this->help,
            placeholder: $this->placeholder,
            required: $this->required,
            readonly: $this->disabled,
            minInputLength: $this->minInputLength,
            key: $this->key,
            filters: $this->filters,
            createNewModel: $this->createNewModel,
            createNewField: $this->createNewField,
            customView: $this->customView,
            fillFields: $this->fillFields
        );
    }
}
