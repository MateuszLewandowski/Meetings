<?php 

namespace App\Service\User;

use App\Repository\UserRepository;
use App\Core\Factory\UserFactory;
use Throwable;

final class CreateUserService implements CreateUserServiceInterface
{
    public function __construct(
        private UserRepository $entityManager,
        private UserFactory $userFactory,
    ) {
    }

    public function add(string $email, ?string $name = null)
    {
        try {
            $user = $this->userFactory->create(
                arguments: [
                    'email' => $email,
                    'name' => $name,
                ],
            );
            $this->entityManager->add(
                entity: $user, flush: true
            );
            return $user;
        } catch (Throwable $e) {
            throw $e;
        }
    }
}