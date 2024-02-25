<?php

namespace App\Controller\Admin;

use App\Entity\ApiKey;
use App\Entity\User;
use App\Form\ApiKeyType;
use App\Repository\ApiKeyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ApiKeyController extends AbstractController
{
    #[Route('/admin/account/api-keys', name: 'admin_api_keys')]
    public function index(ApiKeyRepository $repo, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new HttpException(500, sprintf('Expected App\\Entity\\User, got %s', null === $user ? 'null' : get_class($user)));
        }

        $api_key = new ApiKey();
        $form = $this->createForm(ApiKeyType::class, $api_key);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $api_key->setKey(Uuid::v4());
            $api_key->setAccount($user);

            $manager->persist($api_key);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> La clé d'API a bien été générée !"
            );
        }

        return $this->render('admin/api_keys/index.html.twig', [
            'api_keys' => $repo->findAllByUser($user),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/account/api-keys/{key}/delete', name: 'admin_api_keys_delete')]
    public function delete(ApiKey $api_key, Request $request, EntityManagerInterface $manager): Response
    {
        // Check if the key is the property of the current user
        if ($api_key->getAccount()?->getUserIdentifier() !== $this->getUser()?->getUserIdentifier()) {
            $this->addFlash('danger', "<strong>Erreur :</strong> Vous n'êtes pas autorisé à accéder à cette page !");

            return $this->redirectToRoute('admin_api_keys');
        }

        $confirm = (bool) $request->query->get('confirm');
        $name = $api_key->getLabel() ?? $api_key->getKey();

        if ($confirm) {
            $manager->remove($api_key);
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès !</strong> Le clé <strong>{$name}</strong> a bien été supprimé !"
            );

            return $this->redirectToRoute('admin_api_keys');
        }

        return $this->render('admin/api_keys/delete.html.twig', [
            'api_key' => $api_key,
            'name' => $name,
        ]);
    }
}
