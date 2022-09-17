<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class IsInteger extends AbstractValidator
{
    public function validate(mixed $value): mixed 
    {
        if (!is_integer($value)) {
            throw new InvalidArgumentException('Value must be of integer type.', Response::HTTP_BAD_REQUEST);
        }
        return parent::validate($value);
    }
}
