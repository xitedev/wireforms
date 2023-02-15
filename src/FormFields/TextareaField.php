<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Textarea;
use Xite\Wireforms\Contracts\FieldContract;
use Xite\Wireforms\Traits\Translatable;

class TextareaField extends FormField
{
    use Translatable;

    private ?int $rows = 2;

    public function rows(int $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    protected function render(): FieldContract
    {
        return Textarea::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            rows: $this->rows,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
