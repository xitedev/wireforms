<?php

namespace Xite\Wireforms;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Livewire\Attributes\Computed;
use Livewire\Mechanisms\ComponentRegistry;
use LivewireUI\Modal\ModalComponent;
use Xite\Wireforms\Contracts\FormFieldContract;
use Xite\Wireforms\Enums\NotifyType;
use Xite\Wireforms\Traits\EmitMessages;
use Xite\Wireforms\Traits\HasChild;
use Xite\Wireforms\Traits\HasComponentName;
use Xite\Wireforms\Traits\HasDefaults;

abstract class Form extends ModalComponent
{
    use HasChild;
    use HasDefaults;
    use HasComponentName;
    use EmitMessages;

    public ?string $parentModal = null;
    private array $emitFields = [];

    abstract protected function fields(): Collection;

    abstract protected function title(): string;

    public function beforeSave(): void
    {
//
    }

    public function afterSave(): void
    {
//
    }

    public function rules(): array
    {
        return $this->getFields
            ->filter(fn (FormFieldContract $field) => $field->hasRules())
            ->mapWithKeys(fn (FormFieldContract $field) => $field->getRules())
            ->toArray();
    }

    public function validateField(string $field, $value = null)
    {
        $formField = $this->getFields
            ->first(
                fn (FormFieldContract $formField) => $formField->getNameOrWireModel() === $field
            );

        if (! $formField || ! $formField->hasRules()) {
            return $value;
        }

        return $this
            ->withValidator(
                fn (Validator $validator) => $validator->setData(
                    Arr::undot([$field => $formField->beforeValidate($value)])
                )
            )
            ->validateOnly(
                $formField->getNameOrWireModel(),
                Arr::except($formField->getRules(), 'current')
            );
    }

    #[Computed]
    public function getFields(): Collection
    {
        return $this->fields()
            ->filter(
                fn ($field) => $field instanceof FormFieldContract
            )
            ->filter(
                fn (FormFieldContract $field) => ! method_exists($field, 'canSee') || $field->canRender
            );
    }

    public function freshFields(): void
    {
        unset($this->getFields);
    }

    protected function performSave(): void
    {
        if (! $this->model->isDirty()) {
            $this->emitMessage(NotifyType::INFO, __('wireforms::form.nothing_to_save'));

            return;
        }

        $this->validate();

        $this->model->save();

        $this->emitMessage(NotifyType::SUCCESS, __('wireforms::form.successfully_saved'));
    }

    public function save(): void
    {
        try {
            $this->beforeSave();

            $this->performSave();

            $this->afterSave();

            if (! is_null($this->parentModal)) {
                $this->closeModalWithEvents([
                    ['fillParent.' . $this->parentModal, [$this->model->getKey()]],
                ]);
            } else {
                $this->closeModalWithEvents(['refreshTable']);
            }
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            $this->emitMessage(NotifyType::ERROR, $exception->getMessage());
        }
    }

    protected function renderTitle(): string
    {
        return collect([
            $this->title,
            $this->model->getKey(),
        ])
            ->filter()
            ->implode(' #');
    }

    public function render(): View
    {
        return view('wireforms::form', [
            'fields' => $this->getFields
                ->map(fn (FormFieldContract $field) => $field->renderIt($this->model))
                ->flatten(),
            'title' => $this->renderTitle(),
        ]);
    }
}
