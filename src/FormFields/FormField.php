<?php

namespace Xite\Wireforms\FormFields;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Xite\Wireforms\Contracts\FieldContract;
use Xite\Wireforms\Contracts\FormFieldContract;
use Xite\Wireforms\Traits\HasDisable;
use Xite\Wireforms\Traits\HasRequired;

abstract class FormField implements FormFieldContract
{
    use Conditionable;
    use HasDisable;
    use HasRequired;

    protected $value;
    protected $default;

    protected ?string $placeholder = null;
    protected ?string $help = null;
    protected ?string $key = null;

    protected array $rules = [];
    protected array $classes = [];
    protected array $attributes = [];
    protected int $size = 6;

    protected ?Closure $styleCallback = null;
    protected ?Closure $displayCondition = null;
    protected ?Closure $castCallback = null;

    public bool $exceptFromModel = false;
    public ?string $wireModel = null;

    public function __construct(
        protected string $name,
        protected ?string $label = null
    ) {
        $key = 'model.'.$name;

        $this->wireModel($key);
        $this->keyBy('field-'.$key);
    }

    public static function make(string $name, ?string $label): static
    {
        return new static($name, $label);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function value($value): self
    {
        $this->value = $this->castValue($value);

        return $this;
    }

    public function default($default): self
    {
        $this->default = $this->castValue($default);

        return $this;
    }

    public function wireModel(string $wireModel): self
    {
        $this->wireModel = $wireModel;

        return $this;
    }

    public function exceptFromModel(): self
    {
        $this->wireModel = null;
        $this->exceptFromModel = true;

        return $this;
    }

    public function placeholder(?string $placeholder = null): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function help(string $help): self
    {
        $this->help = $help;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = ($size > 6 || $size < 1) ? 6 : $size;

        return $this;
    }

    public function rules(array $rules): self
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    public function attributes(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);

        return $this;
    }

    public function class(string $classes): self
    {
        $this->classes = array_merge(
            $this->classes,
            explode(' ', $classes)
        );

        return $this;
    }

    public function styleUsing(callable $styleCallback): self
    {
        $this->styleCallback = $styleCallback;

        return $this;
    }

    public function displayIf(callable $displayCondition): self
    {
        $this->displayCondition = $displayCondition;

        return $this;
    }

    public function castUsing(callable $castCallback): self
    {
        $this->castCallback = $castCallback;

        return $this;
    }

    public function keyBy(string $key): self
    {
        $this->key = $key;

        return $this;
    }
    private function colSpan(): string
    {
        return match ($this->size) {
            1 => 'col-span-6 sm:col-span-1',
            2 => 'col-span-6 sm:col-span-2',
            3 => 'col-span-6 sm:col-span-3',
            4 => 'col-span-6 sm:col-span-4',
            5 => 'col-span-6 sm:col-span-5',
            6 => 'col-span-6',
        };
    }

    protected function getClass(?Model $model = null): ?string
    {
        return collect($this->classes)
            ->when(
                is_callable($this->styleCallback) && ! is_null($model),
                fn ($class) => $class->push((string)call_user_func($this->styleCallback, $model))
            )
            ->when(
                $this->size,
                fn ($class) => $class->push($this->colSpan())
            )
            ->filter()
            ->flatten()
            ->unique()
            ->implode(' ');
    }

    public function hasRules(): bool
    {
        return count($this->rules);
    }

    public function getRules(): array
    {
        return [
            $this->getNameOrWireModel() => $this->formatRules(),
        ];
    }

    protected function formatRules(): array
    {
        return collect($this->rules)
            ->when(
                $this->required,
            )
            ->flatten()
            ->toArray();
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    protected function castValue($value)
    {
        if (! is_null($this->castCallback)) {
            return call_user_func($this->castCallback, $value);
        }

        return $value;
    }

    public function beforeValidate($value)
    {
        return $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNameOrWireModel(): string
    {
        return $this->wireModel ?? $this->name;
    }

    public function getDefault()
    {
        return $this->default;
    }

    public function hasDefault(): bool
    {
        return ! is_null($this->default);
    }

    private function canDisplay(?Model $model = null): bool
    {
        return ! is_callable($this->displayCondition) || call_user_func($this->displayCondition, $model);
    }

    public function getKebabName(): string
    {
        return Str::of($this->getName())
            ->camel()
            ->kebab()
            ->prepend('field-')
            ->toString();
    }

    abstract protected function render(): FieldContract;

    public function renderField(?Model $model = null): Collection
    {
        if (! is_null($model) && ! $this->exceptFromModel) {
            $this->value(
                $model->{$this->getName()}
            );
        }

        return collect([
            $this->render(),
        ]);
    }

    public function renderIt(?Model $model = null): ?array
    {
        if (! $this->canDisplay($model)) {
            return null;
        }

        $class = $this->getClass($model);

        return $this
            ->determinateDisabled($model)
            ->determinateRequired($model)
            ->renderField($model)
            ->map(
                fn (FieldContract $field) => $field
                    ->withAttributes($this->getAttributes() + [
                            'class' => $class,
                            'wire:model.debounce.1s' => $field->name,
                        ])
                    ->render()
            )
            ->toArray();
    }
}
