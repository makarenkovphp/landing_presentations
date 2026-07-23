<?php

namespace App\Controller;


use App\Dto\ContactCreatingDto;
use App\Service\ContactService;
use App\View\ContactCreatingView;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

use function dd;

class ContactController extends BaseController
{
    public function __construct(
        private ContactService $service
    )
    {
    }

    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ContactCreatingDto $dto
    ): Response {
        $contact = $this->service->create($dto);

        return $this->json((new ContactCreatingView($contact))->toArray());
    }
}
