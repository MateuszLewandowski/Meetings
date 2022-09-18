<?php 

namespace App\Notification\Email;

use App\Entity\Meeting;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;

final class SendMeetingOwnerOvercrowdedFailure extends AbstractEmail implements SendMeetingOwnerOvercrowdedFailureInterface
{
    private const DATETIME_FORMAT = 'Y-m-d H:i';

    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
        $this->subject = 'A new participant has been registered!';
        $this->message = '
            Trying to join an overcrowded "@meeting_name" event, at @meeting_date, by @user_email.
        ';
    }

    public function send(Meeting $meeting, User $user) 
    {
        return $this->run(
            to: 
                $meeting->getOwner()->getEmail(),
            args: [
                'meeting_name' => $meeting->getName(), 
                'meeting_date' =>$meeting->getDate()->format(self::DATETIME_FORMAT), 
                'user_email' => $user->getEmail()
            ],
        );
    }
}