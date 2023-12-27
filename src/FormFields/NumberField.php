<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Number;
use Xite\Wireforms\Contracts\FieldContract;

class NumberField extends TextField
{
    protected array $rules = ['numeric'];
    private ?float $min = null;
    private ?float $max = null;
    private ?float $step = null;

    public function min(float $min): self
    {
        $this->min = $min;
        $this->rules[] = 'min:'.$min;

        return $this;
    }

    public function max(float $max): self
    {
        $this->max = $max;
        $this->rules[] = 'max:'.$max;

        return $this;
    }

    public function step(float $step): self
    {
        $this->step = $step;

        return $this;
    }

    protected function render(): FieldContract
    {
        return Number::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        )->withAttributes(
            array_filter([
                'min' => (string)$this->min,
                'max' => (string)$this->max,
                'step' => (string)$this->step,
            ])
        );
    }
}
