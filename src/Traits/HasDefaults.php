<?php

namespace Xite\Wireforms\Traits;

use Xite\Wireforms\Contracts\FormFieldContract;

trait HasDefaults
{
    public array $fillFields = [];

    public function mountHasDefaults(): void
    {
        if (! $this->model?->getKey()) {
            $this->getFields
                ->filter(
                    fn (FormFieldContract $field) => $field->hasDefault()
                )
                ->each(fn (FormFieldContract $field) => $this->fillWithHydrate(
                    $field->getNameOrWireModel(),
                    $field->getDefault()
                ));
        }

        if (count($this->fillFields)) {
            $this->getFields
                ->filter(
                    fn (FormFieldContract $field) => isset($this->fillFields[$field->getName()])
                )
                ->each(fn (FormFieldContract $field) => $this->fillWithHydrate(
                    $field->getNameOrWireModel(),
                    $this->fillFields[$field->getName()]
                ));
        }
    }
}
