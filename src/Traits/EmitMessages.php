<?php

namespace Xite\Wireforms\Traits;

use Xite\Wireforms\Enums\NotifyType;

trait EmitMessages
{
    protected function emitMessage(NotifyType $type, string $message): void
    {
        $this->dispatch(
            event: 'notify',
            message: $message,
            type: $type->value
        );
    }
}
