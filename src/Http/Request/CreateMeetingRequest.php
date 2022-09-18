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
    public int $participants_limit;

    public function validate(): bool 
    {
        $this->name = $this->request->get('name', '');
        $this->date = $this->request->get('date', '');
        $this->owner_id = $this->request->get('owner_id', 0);
        $this->participants_limit = $this->request->get('participants_limit', 50);

        return (
            self::validateName(name: $this->name) and 
            self::validateDate(date: $this->date) and 
            self::validateOwnerId(owner_id: $this->owner_id) and
            self::validateParticipantsLimit(participants_limit: $this->participants_limit)
        );
    }

    private static function validateOwnerId(int $owner_id): bool {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->setMiddleware(new IsInteger)
            ->setMiddleware(new IsPositive);
        return $isNotNull->validate(value: $owner_id) === null;
    }

    private static function validateName(string $name): bool {
            $isNotNull = new IsNotNull;
            $isNotNull
                ->setMiddleware(new IsString)
                ->setMiddleware(new IsMeetingNameLongEnough);
            return $isNotNull
                ->validate(value: $name) === null;
    }

    private static function validateDate(string $date): bool {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->setMiddleware(new IsString)
            ->setMiddleware(new MeetingDateHasCorrectFormat)
            ->setMiddleware(new IsDateInTheFuture);
        return $isNotNull->validate(value: $date) === null;
    }

    private static function validateParticipantsLimit(int $participants_limit): bool {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->setMiddleware(new IsInteger)
            ->setMiddleware(new IsPositive);
        return $isNotNull->validate(value: $participants_limit) === null;
    }
}