<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class PostController extends AbstractController
{
    #[Route('/admin/posts', name: 'admin_posts')]
    public function index(PostRepository $repo): Response
    {
        return $this->render('admin/post/index.html.twig', [
            'posts' => $repo->findAll(),
        ]);
    }

    #[Route('/admin/post/new', name: 'admin_post_new')]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $post = new Post();

        $uuid = Uuid::v4();
        $post->setUuid($uuid);

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set uuid here again to be sure to override it
            $post->setUuid($uuid);

            // Check if tags contains primary tag
            if (!$post->getTags()->contains($post->getPrimaryTag()) && $post->getPrimaryTag()) {
                $post->addTag($post->getPrimaryTag());
            }

            $manager->persist($post);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> L'article <strong>{$post->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('admin_post_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('admin/post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/post/{id}/edit', name: 'admin_post_edit')]
    public function edit(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check if tags contains primary tag
            if (!$post->getTags()->contains($post->getPrimaryTag()) && $post->getPrimaryTag()) {
                $post->addTag($post->getPrimaryTag());
            }

            $manager->persist($post);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> L'article <strong>{$post->getTitle()}</strong> a bien été modifiée !"
            );

            return $this->redirectToRoute('admin_post_edit', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/admin/post/{id}/delete', name: 'admin_post_delete')]
    public function delete(Post $post, Request $request, EntityManagerInterface $manager): Response
    {
        $confirm = (bool) $request->query->get('confirm');

        if ($confirm) {
            $manager->remove($post);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> L'article <strong>{$post->getTitle()}</strong> a bien été supprimé !"
            );

            return $this->redirectToRoute('admin_posts');
        }

        return $this->render('admin/post/delete.html.twig', [
            'post' => $post,
        ]);
    }
}
