<?php

namespace App\Controller;

use App\Repository\TechnologyRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTechnologyController extends AbstractController
{
    #[Route('/admin/technologies', name: 'admin_technologies')]
    public function index(TechnologyRepository $repo): Response
    {
        return $this->render('admin/technology/index.html.twig', [
            'technologies' => $repo->findAll(),
        ]);
    }
}
