<?php

namespace App\Http\Request;

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
            self::validateEmail(email: $this->email) and 
            self::validateName(name: $this->name) 
        );
    }

    private static function validateName(?string $name = null): bool
    {
        return is_null($name)
            ? true
            : (new IsString)->validate($name) === null;
    }

    private static function validateEmail(string $email): bool 
    {
        $isNotNull = new IsNotNull;
        $isNotNull
            ->setMiddleware(new IsString)
            ->setMiddleware(new EmailHasCorrectSyntax);
        return $isNotNull->validate(value: $email) === null;
    }
}