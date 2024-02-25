<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request = $event->getRequest();
        $path = $request->getPathInfo();

        // Verify if in API context
        if (str_starts_with($path, '/api') || str_starts_with($path, '/v2')) {
            $status = 500; // return 500 by default when it's not an HTTP exception
            if ($exception instanceof HttpException) {
                $status = $exception->getStatusCode();
            }
            $event->setResponse(new JsonResponse([
                'status' => $status,
                'message' => $exception->getMessage(),
            ], $status));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
