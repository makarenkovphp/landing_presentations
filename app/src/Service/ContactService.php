<?php

namespace App\Service;

use App\Dto\ContactCreatingDto;
use App\Entity\Contact;
use App\Events\ContactCreatedEvent;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class ContactService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ContactRepository $repo,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function create(ContactCreatingDto $dto): Contact
    {
        $contact = new Contact(name: $dto->name, email: $dto->email, phone: $dto->phone, message: $dto->message);
        $this->repo->add($contact);
        $this->em->flush();

        $event = new ContactCreatedEvent($contact->getId());
        $this->dispatcher->dispatch($event);

        return $contact;
    }
}
