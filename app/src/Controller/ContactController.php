<?php

namespace App\Controller;


use App\Dto\ContactCreatingDto;
use App\Service\ContactService;
use App\View\ContactCreatingView;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactoryInterface;
use Symfony\Component\Routing\Attribute\Route;

use function dd;

class ContactController extends BaseController
{
    public function __construct(
        private ContactService $service,
    )
    {
    }

    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ContactCreatingDto $dto,
        Request $request,
        #[Target('api_limiter')] RateLimiterFactoryInterface $rateLimiter,
    ): Response {
        $limiter = $rateLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        $contact = $this->service->create($dto);

        return $this->json((new ContactCreatingView($contact))->toArray());
    }
}
