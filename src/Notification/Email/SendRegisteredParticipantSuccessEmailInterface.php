<?php 

namespace App\Notification\Email;

use App\Entity\Meeting;
use App\Entity\User;

interface SendRegisteredParticipantSuccessEmailInterface
{
    public function send(Meeting $meeting, User $user);
}