<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/admin/tags', name: 'admin_tags')]
    public function index(TagRepository $repo): Response
    {
        return $this->render('admin/tag/index.html.twig', [
            'tags' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/tag/new', name: 'admin_tag_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le tag <strong>{$tag->getName()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_tag_edit', [
                'id' => $tag->getId(),
            ]);
        }

        return $this->render('admin/tag/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/tag/{id}/edit', name: 'admin_tag_edit')]
    public function edit(Tag $tag, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($tag);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le tag <strong>{$tag->getName()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('admin_tag_edit', [
                'id' => $tag->getId(),
            ]);
        }

        return $this->render('admin/tag/edit.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }

    #[Route('/admin/tag/{id}/delete', name: 'admin_tag_delete')]
    public function delete(Tag $tag, Request $request, EntityManagerInterface $manager): Response
    {
        $confirm = (bool) $request->query->get('confirm');

        if ($confirm) {
            $manager->remove($tag);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le tag <strong>{$tag->getName()}</strong> a bien été supprimée !"
            );

            return $this->redirectToRoute('admin_tags');
        }

        return $this->render('admin/tag/delete.html.twig', [
            'tag' => $tag,
        ]);
    }
}
