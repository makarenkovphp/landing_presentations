<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestLoggerSubscriber implements EventSubscriberInterface
{
    private float $startTime;

    public function __construct(
        private LoggerInterface $apiRequestsLogger,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
            KernelEvents::RESPONSE => 'onResponse',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        $this->startTime = microtime(true);

        $request = $event->getRequest();

        $this->apiRequestsLogger->info('Incoming request', [
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'ip' => $request->getClientIp(),
        ]);
    }

    public function onResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $this->apiRequestsLogger->info('Response', [
            'status' => $response->getStatusCode(),
            'time_ms' => round(
                (microtime(true) - $this->startTime) * 1000,
                2
            ),
        ]);
    }
}
