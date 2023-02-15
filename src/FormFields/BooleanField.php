<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Boolean;
use Xite\Wireforms\Contracts\FieldContract;

class BooleanField extends FormField
{
    protected array $rules = [
        'boolean',
    ];

    protected function castValue($value): bool
    {
        return (bool)$value;
    }

    public function render(): FieldContract
    {
        return Boolean::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder
        );
    }
}
