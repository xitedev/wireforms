<?php

namespace Xite\Wireforms\Traits;

use Livewire\Attributes\On;

trait HandleRefreshAfterSave
{
    #[On('formSaved')]
    public function formSaved(): void
    {
        return;
    }
}
