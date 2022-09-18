<?php 

namespace App\Notification\Email;

use App\Entity\Meeting;
use App\Entity\User;

interface SendMeetingOwnerOvercrowdedFailureInterface
{
    public function send(Meeting $meeting, User $user);
}