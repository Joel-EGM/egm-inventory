<?php

namespace App\Http\Interfaces;

interface FieldValidationMessage
{
    public const FIELD_REQUIRED      = 'This is required';
    public const FIELD_STRING_SPACE  = 'This must be a strings and spaces';
    public const FIELD_LENGTH_MIN_1  = 'This must be at least 1 character';
    public const FIELD_LENGTH_MIN_2  = 'This must be at least 2 character';
    public const FIELD_LENGTH_MAX_15 = 'This must not exceed 15 character';
    public const FIELD_LENGTH_MAX_25 = 'This must not exceed 25 character';
    public const FIELD_LENGTH_MAX_50 = 'This must not exceed 50 character';
    public const FIELD_NUMERIC       = 'This must be a number';
    public const FIELD_DATE          = 'This must be a date';
    public const FIELD_STRING_DASH   = 'This must be a strings and dashes';
    public const FIELD_MIME_TYPE     = 'File must be jpg or png format';
}
