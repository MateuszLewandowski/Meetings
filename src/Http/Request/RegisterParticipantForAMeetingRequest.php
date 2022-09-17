<?php

namespace App\Http\Request;

use App\Http\Middleware\Rules\IsInteger;
use App\Http\Middleware\Rules\IsNotNull;
use App\Http\Middleware\Rules\IsPositive;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class RegisterParticipantForAMeetingRequest extends Request implements ValidatableRequestInterface
{
    public int $participant_id;
    public int $event_id;

    public function validate(): bool 
    {
        $this->participant_id = $this->request->get('participant_id', 0);
        $this->event_id = $this->request->get('event_id', 0);

        return (
            self::validateEventOrParticipantId(id: $this->participant_id) and
            self::validateEventOrParticipantId(id: $this->event_id) 
        );
    }

    private static function validateEventOrParticipantId(string $id): bool 
    {
        return (new IsNotNull)
            ->setMiddleware(new IsInteger)
            ->setMiddleware(new IsPositive)
            ->validate(value: $id) === null;
    }
}