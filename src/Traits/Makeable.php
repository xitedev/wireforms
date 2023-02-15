<?php

namespace Xite\Wireforms\Traits;

trait Makeable
{
    public static function make(...$attributes): static
    {
        return new static(...$attributes);
    }
}
