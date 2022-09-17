<?php

namespace App\Http\Middleware\Rules;

use App\Http\Middleware\AbstractValidator;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class MeetingDateHasCorrectFormat extends AbstractValidator
{
    private const FORMAT = 'Y-m-d H:i';

    public function validate(mixed $value): mixed 
    {
        if (date(self::FORMAT, strtotime($value)) !== $value) {
            throw new InvalidArgumentException('Invalid date format, ex. 2022.09.16 20:00', Response::HTTP_BAD_REQUEST);
        }
        
        return parent::validate($value);
    }
}
