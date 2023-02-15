<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Text;
use Xite\Wireforms\Contracts\FieldContract;
use Xite\Wireforms\Traits\Translatable;

class TextField extends FormField
{
    use Translatable;

    public ?string $type = 'text';

    public function type(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function number(): self
    {
        $this->type = 'number';
        $this->rules[] = 'numeric';

        return $this;
    }

    protected function render(): FieldContract
    {
        return Text::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            type: $this->type,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
