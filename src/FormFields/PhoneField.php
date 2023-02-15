<?php

namespace Xite\Wireforms\FormFields;

use Illuminate\Validation\Rule;
use Xite\Wireforms\Components\Fields\Phone;
use Xite\Wireforms\Contracts\FieldContract;

class PhoneField extends FormField
{
    protected string $country;

    public function country(string $country): self
    {
        $this->country = $country;
        $this->rules[] = Rule::phone()->country($country);

        return $this;
    }

    protected function render(): FieldContract
    {
        return Phone::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
