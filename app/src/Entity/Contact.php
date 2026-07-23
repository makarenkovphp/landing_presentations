<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'contact')]
#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
    #[Column(type: Types::STRING)]
    private string $name;
    #[Column(type: Types::STRING, length: 255)]
    private string $email;
    #[Column(type: Types::STRING, length: 16)]
    private string $phone;
    #[Column(type: Types::TEXT)]
    private string $message;

    public function __construct(
        string $name,
        string $email,
        string $phone,
        string $message
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->message = $message;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

}
