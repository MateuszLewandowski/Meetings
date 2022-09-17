<?php 

namespace App\Http\Request;

interface ValidatableRequestInterface
{
    public function validate(): bool;
}