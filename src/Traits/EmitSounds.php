<?php

namespace Xite\Wireforms\Traits;

use Xite\Wireforms\Enums\SoundType;

trait EmitSounds
{
    protected function emitSound(SoundType $sound): void
    {
        $this->dispatch(
            event: 'sound',
            sound: $sound->value
        );
    }
}
