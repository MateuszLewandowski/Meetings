<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class IsMeetingNameLongEnough extends AbstractValidator
{
    private const LENGTH = 5;

    public function validate(mixed $value): mixed 
    {
        if (strlen($value) < self::LENGTH) {
            throw new InvalidArgumentException('Event name is not long enough.', Response::HTTP_BAD_REQUEST);
        }
        return parent::validate($value);
    }
}
