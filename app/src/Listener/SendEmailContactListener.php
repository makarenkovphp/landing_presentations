<?php

namespace App\Listener;

use App\Events\ContactCreatedEvent;
use App\Repository\ContactRepository;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsEventListener]
class SendEmailContactListener
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail,
        private string $ownerEmail,
        private ContactRepository $repository,
    )
    {
    }

    public function __invoke(ContactCreatedEvent $event): void
    {
        $contact = $this->repository->findById( $event->getId());
        if ($contact === null) {
            // logger
            return;
        }

        $email = (new Email())
        ->from($this->adminEmail)
        ->to($this->ownerEmail)
        ->cc($contact->getEmail())
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Ваш контакт принят!')
        ->html($this->createBody($contact->getMessage()));

        $this->mailer->send($email);
    }

    private function createBody(string $body): string
    {
        return sprintf('<em>комментарий:</em><p>%s</p>', $body);
    }
}
