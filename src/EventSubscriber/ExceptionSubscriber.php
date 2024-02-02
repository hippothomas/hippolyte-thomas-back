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
        if (str_starts_with($path, '/api')) {
            if ($exception instanceof HttpException) {
                $data = [
                    'status' => $exception->getStatusCode(),
                    'message' => $exception->getMessage(),
                ];

                $event->setResponse(new JsonResponse($data));
            } else {
                $data = [
                    'status' => 500, // return 500 by default when it's not an HTTP exception
                    'message' => $exception->getMessage(),
                ];

                $event->setResponse(new JsonResponse($data));
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
