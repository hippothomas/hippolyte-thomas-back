<?php

namespace App\Controller;

use App\Form\AboutMeType;
use App\Repository\AboutMeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAboutMeController extends AbstractController
{
    #[Route('/admin/about-me', name: 'admin_about_me')]
    public function edit(AboutMeRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        $about_me = $repo->findAll()[0];

        $form = $this->createForm(AboutMeType::class, $about_me);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $about_me->getPicture();
            $manager->persist($picture);

            $manager->persist($about_me);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Les informations ont bien été modifiées !"
            );

            return $this->redirectToRoute("admin_about_me");
        }

        return $this->render('admin/about_me/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
