<?php 

namespace App\Service\User;

interface CreateUserServiceInterface
{
    public function add(string $email, ?string $name = null);
}