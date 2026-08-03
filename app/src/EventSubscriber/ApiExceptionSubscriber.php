<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $apiRequestsLogger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onException',
        ];
    }

    public function onException(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $request = $event->getRequest();

        // Логируем любую ошибку
        $this->apiRequestsLogger->error('API exception', [
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'ip' => $request->getClientIp(),
            'exception' => get_class($e),
            'message' => $e->getMessage(),
        ]);

        // Ошибки валидации MapRequestPayload
        $validationException = $e;

        if (!$validationException instanceof ValidationFailedException) {
            $validationException = $e->getPrevious();
        }

        if ($validationException instanceof ValidationFailedException) {
            $errors = [];

            foreach ($validationException->getViolations() as $violation) {
                $errors[] = [
                    'field' => $violation->getPropertyPath(),
                    'message' => $violation->getMessage(),
                    'value' => $violation->getInvalidValue(),
                ];
            }

            $event->setResponse(new JsonResponse([
                'success' => false,
                'errors' => $errors,
            ], 422));

            return;
        }

        // Все остальные ошибки
        $status = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : 500;

        $event->setResponse(new JsonResponse([
            'success' => false,
            'message' => $e->getMessage(),
        ], $status));
    }
}
