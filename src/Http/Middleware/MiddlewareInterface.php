<?php 

namespace App\Http\Middleware;

interface MiddlewareInterface
{
    public function setMiddleware(MiddlewareInterface $middleware): MiddlewareInterface;
    public function validate(mixed $value): mixed;
}