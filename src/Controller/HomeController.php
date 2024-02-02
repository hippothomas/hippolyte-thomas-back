<?php

namespace App\Controller;

use App\Constants;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function index(): RedirectResponse
    {
        return $this->redirect(Constants::MAIN_WEBSITE, 301);
    }
}
