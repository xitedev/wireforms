<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\MultiSelect;
use Xite\Wireforms\Contracts\FieldContract;

class MultiSelectField extends FormField
{
    protected array $options = [];
    protected bool $nullable = false;

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;

        return $this;
    }

    protected function render(): FieldContract
    {
        return MultiSelect::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            options: $this->options,
            nullable: $this->nullable,
            label: $this->label,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
