<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;

class FileUpload extends Field
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
        ?string $innerClass = null
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
        return view('wireforms::components.fields.file-upload')
            ->with($this->data());
    }
}
