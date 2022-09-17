<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class IsPositive extends AbstractValidator
{
    public function validate(mixed $value): mixed 
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('The number is not positive.', Response::HTTP_BAD_REQUEST);
        }
        return parent::validate($value);
    }
}
