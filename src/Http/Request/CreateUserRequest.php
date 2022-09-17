<?php

namespace App\Requests;

use App\Http\Middleware\Rules\EmailHasCorrectSyntax;
use App\Http\Middleware\Rules\IsNotNull;
use App\Http\Middleware\Rules\IsString;
use App\Http\Request\ValidatableRequestInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateUserRequest extends Request implements ValidatableRequestInterface
{
    public ?string $name;
    public string $email;

    public function validate(): bool 
    {
        $this->name = $this->request->get('name', null);
        $this->email = $this->request->get('email', '');

        return (
            self::validateName(name: $this->name) and 
            self::validateEmail(email: $this->email) 
        );
    }

    private static function validateName(?string $name = null): bool
    {
        if (is_null($name)) {
            return true;
        }
        return (new IsString)->validate($name) === null;
    }

    private static function validateEmail(string $email): bool 
    {
        return (new IsNotNull)
            ->setMiddleware(new IsString)
            ->setMiddleware(new EmailHasCorrectSyntax)
            ->validate(value: $email) === null;
    }
}