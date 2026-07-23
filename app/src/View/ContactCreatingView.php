<?php

namespace App\View;

use App\Entity\Contact;

class ContactCreatingView
{
    public function __construct(private Contact $contact)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->contact->getId(),
            'name' => $this->contact->getName(),
            'email' => $this->contact->getEmail(),
            'phone' => $this->contact->getPhone(),
            'message' => $this->contact->getMessage(),
        ];
    }
}
