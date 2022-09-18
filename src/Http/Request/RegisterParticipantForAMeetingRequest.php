<?php

namespace App\Http\Request;

use App\Http\Middleware\Rules\IsInteger;
use App\Http\Middleware\Rules\IsNotNull;
use App\Http\Middleware\Rules\IsPositive;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class RegisterParticipantForAMeetingRequest extends Request implements ValidatableRequestInterface
{
    public int $user_id;
    public int $meeting_id;

    public function validate(): bool 
    {
        $this->user_id = $this->request->get('user_id', 0);
        $this->meeting_id = $this->request->get('meeting_id', 0);

        return (
            self::validateEventOrParticipantId(id: $this->meeting_id) and
            self::validateEventOrParticipantId(id: $this->user_id) 
        );
    }

    private static function validateEventOrParticipantId(int $id): bool 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->setMiddleware(new IsInteger)
            ->setMiddleware(new IsPositive);
        return $isNotNull->validate(value: $id) === null;
    }
}