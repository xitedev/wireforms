<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Time;
use Xite\Wireforms\Contracts\FieldContract;

class TimeField extends FormField
{
    private bool $allowClear = false;

    public function allowClear(): self
    {
        $this->allowClear = true;

        return $this;
    }

    protected function render(): FieldContract
    {
        return Time::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            allowClear: $this->allowClear,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
