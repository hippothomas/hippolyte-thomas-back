<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    #[Route('/login', name: 'account_login')]
    public function index(AuthenticationUtils $utils): Response
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => null !== $error,
            'username' => $username,
        ]);
    }

    #[Route('/logout', name: 'account_logout')]
    public function logout(): void
    {
        // Handled by Symfony
    }

    #[Route('/register', name: 'account_register')]
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher): Response
    {
        $register = $this->getParameter('app.register');

        if ('true' == $register) {
            $user = new User();

            $form = $this->createForm(RegistrationType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $hasher->hashPassword(
                    $user, $user->getPassword()
                );
                $user->setPassword($password);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre compte a bien été créé ! Vous pouvez maintenant vous connecter !'
                );

                return $this->redirectToRoute('account_login');
            }

            return $this->render('admin/account/registration.html.twig', [
                'form' => $form->createView(),
            ]);
        } else {
            $this->addFlash(
                'warning',
                'La création de compte est fermée pour le moment !'
            );

            return $this->redirectToRoute('account_login');
        }
    }
}
