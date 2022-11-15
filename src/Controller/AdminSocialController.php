<?php

namespace App\Controller;

use App\Repository\SocialRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminSocialController extends AbstractController
{
    #[Route('/admin/socials', name: 'admin_socials')]
    public function index(SocialRepository $repo): Response
    {
        return $this->render('admin/social/index.html.twig', [
            'socials' => $repo->findAll(),
        ]);
    }
}
