<?php

namespace App\Controller;

use App\Entity\Social;
use App\Form\SocialType;
use App\Repository\SocialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    
    #[Route('/admin/social/new', name: 'admin_social_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $social = new Social();

        $form = $this->createForm(SocialType::class, $social);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $social->getPicture();
            $manager->persist($picture);

            $manager->persist($social);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le réseau <strong>{$social->getName()}</strong> a bien été enregistré !"
            );

            return $this->redirectToRoute("admin_social_edit", [
                "id" => $social->getId()
            ]);
        }

        return $this->render('admin/social/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/admin/social/{id}/edit', name: 'admin_social_edit')]
    public function edit(Social $social, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(SocialType::class, $social);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $social->getPicture();
            $manager->persist($picture);

            $manager->persist($social);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le réseau <strong>{$social->getName()}</strong> a bien été modifié !"
            );

            return $this->redirectToRoute("admin_social_edit", [
                "id" => $social->getId()
            ]);
        }

        return $this->render('admin/social/edit.html.twig', [
            'form' => $form->createView(),
            'social' => $social,
        ]);
    }

    #[Route('/admin/social/{id}/delete', name: 'admin_social_delete')]
    public function delete(Social $social, Request $request, EntityManagerInterface $manager): Response
    {
        $confirm = (bool) $request->query->get('confirm');

        if ($confirm) {
            $picture = $social->getPicture();
            $manager->remove($picture);

            $manager->remove($social);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le réseau <strong>{$social->getName()}</strong> a bien été supprimé !"
            );

            return $this->redirectToRoute("admin_socials");
        }

        return $this->render('admin/social/delete.html.twig', [
            'social' => $social,
        ]);
    }
}
