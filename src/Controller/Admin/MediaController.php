<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MediaController extends AbstractController
{
    #[Route('/admin/medias', name: 'admin_medias')]
    public function index(MediaRepository $repo, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $repo->findAllQuery(),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('admin/media/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/admin/media/new', name: 'admin_media_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $media = new Media();

        $form = $this->createForm(MediaType::class, $media);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($media);
            $manager->flush();

            $name = $media->getCaption() ?? $media->getFileName();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le Média <strong>{$name}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_media_edit', [
                'id' => $media->getId(),
            ]);
        }

        return $this->render('admin/media/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/media/{id}/edit', name: 'admin_media_edit')]
    public function edit(Media $media, PostRepository $postRepository, Request $request, EntityManagerInterface $manager): Response
    {
        $name = $media->getCaption() ?? $media->getFileName();
        $posts = $postRepository->findByMedia($media);

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($media);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le Média <strong>{$name}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('admin_media_edit', [
                'id' => $media->getId(),
            ]);
        }

        return $this->render('admin/media/edit.html.twig', [
            'form' => $form->createView(),
            'media' => $media,
            'name' => $name,
            'related_posts' => $posts,
        ]);
    }

    #[Route('/admin/media/{id}/delete', name: 'admin_media_delete')]
    public function delete(Media $media, Request $request, EntityManagerInterface $manager): Response
    {
        $confirm = (bool) $request->query->get('confirm');
        $name = $media->getCaption() ?? $media->getFileName();

        if ($confirm) {
            $manager->remove($media);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le Média <strong>{$name}</strong> a bien été supprimé !"
            );

            return $this->redirectToRoute('admin_medias');
        }

        return $this->render('admin/media/delete.html.twig', [
            'media' => $media,
            'name' => $name,
        ]);
    }
}
