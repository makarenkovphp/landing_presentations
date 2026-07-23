<?php

namespace App\Service;

use App\Dto\ContactCreatingDto;
use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    public function __construct(
        private EntityManagerInterface $em,
        private ContactRepository $repo,
    ) {
    }

    public function create(ContactCreatingDto $dto): Contact
    {
        $contact = new Contact(name: $dto->name, email: $dto->email, phone: $dto->phone, message: $dto->message);
        $this->repo->add($contact);
        $this->em->flush();

        return $contact;
    }
}
