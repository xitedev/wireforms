<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;

class Time extends Field
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
        public bool $allowClear = false,
        public ?string $prepend = null,
        public ?string $append = null
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

    public function render(): View
    {
        return view('wireforms::components.fields.time')
            ->with($this->data());
    }
}
