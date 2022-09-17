<?php

namespace App\Http\Request;

use App\Http\Middleware\Rules\IsDateInTheFuture;
use App\Http\Middleware\Rules\IsInteger;
use App\Http\Middleware\Rules\IsMeetingNameLongEnough;
use App\Http\Middleware\Rules\IsNotNull;
use App\Http\Middleware\Rules\IsPositive;
use App\Http\Middleware\Rules\IsString;
use App\Http\Middleware\Rules\MeetingDateHasCorrectFormat;
use Symfony\Component\HttpFoundation\Request;

final class CreateMeetingRequest extends Request implements ValidatableRequestInterface
{
    public string $name;
    public string $date;
    public int $owner_id;

    public function validate(): bool {
        
        $this->name = $this->request->get('name', '');
        $this->date = $this->request->get('date', '');
        $this->owner_id = $this->request->get('owner_id', 0);

        return (
            self::validateName(name: $this->name) and 
            self::validateDate(date: $this->date) and 
            self::validateOwnerId(owner_id: $this->owner_id)
        );
    }

    private static function validateOwnerId(int $owner_id): bool {
        return (new IsNotNull)
            ->setMiddleware(new IsInteger)
            ->setMiddleware(new IsPositive)
            ->validate(value: $owner_id) === null;
    }

    private static function validateName(string $name): bool {
        return (new IsNotNull)
            ->setMiddleware(new IsString)
            ->setMiddleware(new IsMeetingNameLongEnough)
            ->validate(value: $name) === null;
    }

    private static function validateDate(string $date): bool {
        return (new IsNotNull)
            ->setMiddleware(new IsString)
            ->setMiddleware(new MeetingDateHasCorrectFormat)
            ->setMiddleware(new IsDateInTheFuture)
            ->validate(value: $date) === null;
    }
}