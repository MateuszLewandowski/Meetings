<?php 

namespace App\Service\Participant;

interface RegisterParticipantForAMeetingServiceInterface
{
    public function add(int $meeting_id, int $user_id);
}