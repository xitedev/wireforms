<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;

class NestedSetSelect extends WireSelect
{
    public function render(): View
    {
        return view('wireforms::components.fields.nested-set-select')
            ->with($this->data());
    }
}
