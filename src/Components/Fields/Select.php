<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;

class Select extends Field
{
    public function __construct(
        string $name,
        $value = null,
        bool $required = false,
        bool $disabled = false,
        bool $readonly = false,
        bool $showLabel = true,
        ?string $label = null,
        ?string $key = null,
        ?string $placeholder = null,
        ?string $help = null,
        ?string $innerClass = null,
        public ?int $limit = 20,
        public ?int $minInputLength = null,
        public ?string $emitUp = null,
        public array $options = [],
        public bool $nullable = false,
        public bool $searchable = false,
    ) {
        parent::__construct(
            $name,
            $value,
            $required,
            $disabled,
            $readonly,
            $showLabel,
            $label,
            $key,
            $placeholder,
            $help,
            $innerClass
        );
    }

    public function isSelected($value): bool
    {
        return (string)$this->value === (string)$value;
    }

    public function render(): View
    {
        return view('wireforms::components.fields.select')
            ->with($this->data());
    }
}
