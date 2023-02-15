<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Hidden;
use Xite\Wireforms\Contracts\FieldContract;

class HiddenField extends FormField
{
    protected function render(): FieldContract
    {
        return Hidden::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            key: $this->key,
        );
    }
}
