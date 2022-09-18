<?php 

namespace App\Http\Middleware;


abstract class AbstractValidator implements MiddlewareInterface
{
    private $nextMiddleware;

    public function setMiddleware(MiddlewareInterface $middleware): MiddlewareInterface {
        $this->nextMiddleware = $middleware;
        return $middleware;
    }

    public function validate(mixed $value): mixed {
        return $this->nextMiddleware
            ? $this->nextMiddleware->validate($value)
            : null;
    }
}