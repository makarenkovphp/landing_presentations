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
        #[Target('api_limiter')]
        private RateLimiterFactoryInterface $rateLimiter,
    )
    {
    }

    #[Route('/contact', name: 'contact', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ContactCreatingDto $dto,
        Request $request,
    ): Response {
        $limiter = $this->rateLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            throw new TooManyRequestsHttpException();
        }

        $contact = $this->service->create($dto);

        return $this->createdResponse(
            (new ContactCreatingView($contact))->toArray()
        );
    }
}
