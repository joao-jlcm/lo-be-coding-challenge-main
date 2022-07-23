<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/count', methods: ['GET'])]
    public function count(Request $request): Response
    {
        $logRepository = $this->entityManager->getRepository(Log::class);
        
        return $this->json(['count' => $logRepository->countByParams([
            'serviceNames' => $request->query->get('serviceNames') ?? null,
            'statusCode' => $request->query->get('statusCode') ?? null,
            'startDate' => $request->query->get('startDate') ?? null,
            'endDate' => $request->query->get('endDate') ?? null,
        ])]);
    }
}
