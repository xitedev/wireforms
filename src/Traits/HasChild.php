<?php

namespace Xite\Wireforms\Traits;

use Illuminate\Support\Str;
use Livewire\Attributes\On;

trait HasChild
{
    protected function fillWithHydrate($key, $value = null): void
    {
        $this->fill([
            $key => $value === '' ? null : $value,
        ]);

        $method = Str::of($key)
            ->replace('.', '_')
            ->prepend('updated_')
            ->camel()
            ->toString();

        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    #[On('updatedChild')]
    public function updatedChild(string $key, $value = null): void
    {
        if (method_exists($this, 'validateField')) {
            $this->validateField($key, $value);
        }

        $this->fillWithHydrate($key, $value);
    }
}
