<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class IsString extends AbstractValidator
{
    public function validate(mixed $value): mixed 
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Value must be of string type.', Response::HTTP_BAD_REQUEST);
        }
        return parent::validate($value);
    }
}
