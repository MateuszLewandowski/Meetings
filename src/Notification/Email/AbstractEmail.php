<?php 

namespace App\Notification\Email;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Throwable;

abstract class AbstractEmail 
{
    protected MailerInterface $mailer;
    protected string $from = 'no-reply@meetings.com';
    protected string $subject;
    protected string $message;

    protected function run(string $to, array $args = []) 
    {
        try {
            return $this->mailer->send(
                message: (new Email())
                    ->from($this->from)
                    ->to($to)
                    ->subject($this->subject)
                    ->text(
                        empty($args) 
                            ? $this->message 
                            : $this->prepareTextMessage($args)
                    )
            );
        } catch (Throwable $e) {
            throw $e;
        }
    }

    protected function prepareTextMessage(array $args): string
    {
        foreach ($args as $key => $value) {
            $this->message = str_replace(
                search: '@' . $key, 
                replace: $value, 
                subject: $this->message
            );
        }
        return trim($this->message);
    }
}