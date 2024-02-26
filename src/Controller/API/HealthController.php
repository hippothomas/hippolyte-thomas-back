<?php

namespace App\Controller\API;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('/v2/health', name: 'api_health', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $healthy = true;

        // Check filesystem available space (100 KiB mini.)
        $bytes = disk_free_space('.');
        if (false === $bytes || $bytes < (1024 * 100)) {
            $this->logger->warning('[HealthController][filesystem] There is less than 100KiB available on the filesystem.');
            $healthy = false;
        }

        // Check Doctrine connection
        try {
            // Try to establish the connection
            $this->em->getConnection()->connect();
        } catch (\Exception $e) {
            $this->logger->alert('[HealthController][Doctrine] Exception: {exception}', [
                'exception' => $e->getMessage(),
            ]);
            $healthy = false;
        }
        // Check the connection
        if (!$this->em->getConnection()->isConnected()) {
            $this->logger->alert('[HealthController][Doctrine] Error while checking connection to the database.');
            $healthy = false;
        }

        return new JsonResponse([
            'healthy' => $healthy,
        ], Response::HTTP_OK);
    }
}
