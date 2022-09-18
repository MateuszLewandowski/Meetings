<?php 

namespace App\Http\DTO;

final class UserDTO 
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name,
        public readonly string $email,
        public readonly array $meetings,
    ) {
    }
}