<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;

class Checkbox extends Field
{
    public function render(): View
    {
        return view('wireforms::components.fields.checkbox')
            ->with($this->data());
    }
}
