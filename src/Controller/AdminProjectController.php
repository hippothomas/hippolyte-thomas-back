<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminProjectController extends AbstractController
{
    #[Route('/admin/projects', name: 'admin_projects')]
    public function index(ProjectRepository $repo): Response
    {
        return $this->render('admin/project/index.html.twig', [
            'projects' => $repo->findAll(),
        ]);
    }
}
