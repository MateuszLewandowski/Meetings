<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class IsNotNull extends AbstractValidator
{
    public function validate(mixed $value): mixed 
    {
        if (is_null($value) or $value === '' or empty($value)) {
            throw new InvalidArgumentException('Value must not be null nor empty.', Response::HTTP_BAD_REQUEST);
        }
        return parent::validate($value);
    }
}
