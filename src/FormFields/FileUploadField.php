<?php

namespace Xite\Wireforms\FormFields;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Xite\Wireforms\Components\Fields\FileUpload;
use Xite\Wireforms\Contracts\FieldContract;

class FileUploadField extends FormField
{
    public function __construct(
        protected string $name,
        protected ?string $label = null
    ) {
        parent::__construct($name, $label);
        $this->exceptFromModel();
    }

    public function renderField(?Model $model = null): Collection
    {
        if (! is_null($model)) {
            $this->value(
                $model->getFirstMedia($this->getName())
            );
        }

        return collect([
            $this->render(),
        ]);
    }

    protected function render(): FieldContract
    {
        return FileUpload::make(
            name: $this->getNameOrWireModel(),
            value: $this->value,
            label: $this->label,
            help: $this->help,
            key: $this->key,
            placeholder: $this->placeholder,
            required: $this->required,
            disabled: $this->disabled
        );
    }
}
