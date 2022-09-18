<?php 

namespace App\Core\Factory;

use App\Entity\User;
use Throwable;

final class UserFactory extends FactoryAbstract
{
    public function __construct(
    ) {
        $this->required = ['email'];
    }

    public function create(array $arguments = []): User
    {
        try {
            return $this->make(
                object: new User, arguments: $arguments
            );
        } catch (Throwable $e) {
            throw $e;
        }
    }
}