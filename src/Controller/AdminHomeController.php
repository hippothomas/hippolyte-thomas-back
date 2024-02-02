<?php

namespace App\Controller;

use App\Repository\ProjectRepository;
use App\Repository\SocialRepository;
use App\Repository\TechnologyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AbstractController
{
    #[Route('/admin/', name: 'admin_home')]
    public function index(ProjectRepository $projects, SocialRepository $socials, TechnologyRepository $technologies): Response
    {
        return $this->render('admin/home/index.html.twig', [
            'projects' => $projects->findBy([], ['id' => 'DESC'], 5),
            'socials' => $socials->findBy([], ['id' => 'DESC'], 5),
            'technologies' => $technologies->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}
