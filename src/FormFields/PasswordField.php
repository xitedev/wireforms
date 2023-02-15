<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Text;
use Xite\Wireforms\Contracts\FieldContract;

class PasswordField extends FormField
{
    public function __construct(
        protected string $name,
        protected ?string $label = null
    ) {
        parent::__construct($name, $label);

        $this->exceptFromModel();
    }

    protected function render(): FieldContract
    {
        return Text::make(
            name: $this->getName(),
            value: $this->value,
            label: $this->label,
            type: 'password',
            key: $this->key,
            help: $this->help,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
