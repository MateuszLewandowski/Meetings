<?php 

namespace App\Service\Meeting;

use App\Entity\Meeting;

interface CreateMeetingServiceInterface
{
    public function add(int $owner_id, string $name, string $date, int $participants_limit): ?Meeting;
}