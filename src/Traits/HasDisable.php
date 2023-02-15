<?php

namespace Xite\Wireforms\Traits;

use Closure;
use Illuminate\Database\Eloquent\Model;

trait HasDisable
{
    protected bool $disabled = false;
    protected ?Closure $disabledCondition = null;

    public function isDisabled(callable $disabledCondition): self
    {
        $this->disabledCondition = $disabledCondition;

        return $this;
    }

    public function disabled(): self
    {
        $this->disabled = true;

        return $this;
    }

    private function determinateDisabled(?Model $model = null): self
    {
        if (is_callable($this->disabledCondition)) {
            $this->disabled = (bool)call_user_func($this->disabledCondition, $model);
        }

        return $this;
    }
}
