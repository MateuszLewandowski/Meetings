<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class IsDateInTheFuture extends AbstractValidator
{
    private const FORMAT = 'Y-m-d H:i';

    public function validate(mixed $value): mixed 
    {
        if ($value <= date(self::FORMAT)) {
            throw new InvalidArgumentException('The date of the event must be in the future, ex. tomorrow.', Response::HTTP_BAD_REQUEST);
        }
        return parent::validate($value);
    }
}
