<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ContactCreatingDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 2, max: 500)]
        public readonly string $name,
        #[Assert\NotBlank]
        #[Assert\Regex(pattern: '/^\+7\s?\(?\d{3}\)?\s?\d{3}[-\s]?\d{2}[-\s]?\d{2}$/')]
        public readonly string $phone,
        #[Assert\NotBlank]
        #[Assert\Email]
        #[Assert\Length(min: 2, max: 255)]
        public readonly string $email,
        #[Assert\NotBlank]
        public readonly string $message,
    ) {
    }
}
