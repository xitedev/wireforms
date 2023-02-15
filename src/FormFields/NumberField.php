<?php

namespace Xite\Wireforms\FormFields;

use Xite\Wireforms\Components\Fields\Text;
use Xite\Wireforms\Contracts\FieldContract;

class NumberField extends TextField
{
    public ?string $type = 'number';
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
        )->withAttributes(
            array_filter([
                'min' => $this->min,
                'max' => $this->max,
                'step' => $this->step,
            ])
        );
    }
}
