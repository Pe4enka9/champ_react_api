<?php

namespace App\Enums;

enum ObjectTypeEnum: string
{
    case TEXT = 'text';
    case IMAGE = 'image';
    case RECTANGLE = 'rectangle';
    case CIRCLE = 'circle';
    case LINE = 'line';
}
