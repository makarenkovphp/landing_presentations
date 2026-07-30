<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Exception\NotFoundHttpException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    public function add(Contact $contact): void
    {
        $this->getEntityManager()->persist($contact);
    }

    public function getById(int $id): Contact
    {
        $contact = $this->find($id);
        if (null === $contact) {
            throw new NotFoundHttpException('Contact not found');
        }

        return $contact;
    }

    public function findById(int $id): ?Contact
    {
         return $this->find($id);
    }
}
