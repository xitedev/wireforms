<?php

namespace Xite\Wireforms\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

trait Authorizable
{
    use AuthorizesRequests;
    public bool $canRender = true;

    public function canSee(string $ability, string $model): self
    {
        if ($this->canRender === false) {
            return $this;
        }

        $this->canRender = $this->authorizeModel($ability, $model);

        return $this;
    }

    public function canSeeWhen(callable $condition): self
    {
        $this->canRender = call_user_func($condition);

        return $this;
    }


    private function authorizeModel(string $ability, Model|string $model): bool
    {
        try {
            $this->authorize($ability, $model);

            return true;
        } catch (AuthorizationException) {
            return false;
        }
    }
}
