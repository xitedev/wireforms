<?php

namespace Xite\Wireforms\Components\Fields;

use Illuminate\Contracts\View\View;

class Number extends Field
{
    public function render(): View
    {
        return view('wireforms::components.fields.number')
            ->with($this->data());
    }
}
