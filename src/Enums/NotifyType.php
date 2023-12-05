<?php

namespace Xite\Wireforms\Enums;

enum NotifyType: string
{
    case SUCCESS = 'success';
    case ERROR = 'error';
    case INFO = 'info';
    case WARNING = 'warning';
}
