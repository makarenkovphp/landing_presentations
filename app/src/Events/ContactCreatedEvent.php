<?php

namespace App\Events;

class ContactCreatedEvent
{
    public function __construct(private int $id)
    {
    }

    public function getId():int
    {
        return $this->id;
    }
}
