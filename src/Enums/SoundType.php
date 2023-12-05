<?php

namespace Xite\Wireforms\Enums;

enum SoundType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case CHECK = 'check';
    case FOUND = 'found';
    case FINISH = 'finish';
    case ALARM = 'alarm';
}
