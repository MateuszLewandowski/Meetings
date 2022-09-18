<?php 

namespace App\Notification\Email;

use App\Entity\Meeting;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;

final class SendRegisteredParticipantSuccessEmail extends AbstractEmail implements SendRegisteredParticipantSuccessEmailInterface
{
    private const DATETIME_FORMAT = 'Y-m-d H:i';

    public function __construct(
        MailerInterface $mailer
    ) {
        $this->mailer = $mailer;
        $this->subject = 'A new participant has been registered!';
        $this->message = '
            Thank you for registering to "@meeting_name" event that will be held on @meeting_date
        ';
    }

    public function send(Meeting $meeting, User $user) 
    {
        return $this->run(
            to: 
                $user->getEmail(),
            args: [
                'meeting_name' => $meeting->getName(), 
                'meeting_date' =>$meeting->getDate()->format(self::DATETIME_FORMAT), 
            ],
        );
    }
}