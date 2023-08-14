<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class WireMultiSelect extends Field
{
    public function __construct(
        string $name,
        $value,
        bool $required = false,
        bool $disabled = false,
        bool $readonly = false,
        bool $showLabel = true,
        ?string $label = null,
        ?string $key = null,
        ?string $placeholder = null,
        ?string $help = null,
        ?string $innerClass = null,
        public ?string $model = null,
        public ?string $createNewModel = null,
        public ?string $createNewField = null,
        public bool $nullable = false,
        public bool $searchable = false,
        public ?int $minInputLength = null,
        public ?string $orderBy = null,
        public ?string $orderDir = null,
        public ?Collection $filters = null,
        public ?string $emitUp = null,
        public ?string $customView = null,
        public ?array $fillFields = []
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
        return view('wireforms::components.fields.wire-multi-select')
            ->with($this->data());
    }
}
