<?php 

namespace App\Http\DTO;

final class MeetingDTO 
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $date,
        public readonly array $owner,
        public readonly array $participants,
        public readonly int $participants_limit,
    ) {
    }
}