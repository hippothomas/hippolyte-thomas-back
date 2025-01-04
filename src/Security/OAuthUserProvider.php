<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @implements UserProviderInterface<User>
 */
class OAuthUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {
    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @throws \RuntimeException if the provider is not handled
     */
    #[\Override]
    public function loadUserByOAuthUserResponse(UserResponseInterface $response): ?User
    {
        $resource_owner_name = $response->getResourceOwner()->getName();

        if ('keycloak' !== $resource_owner_name) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resource_owner_name));
        }

        $identifier = $response->getUserIdentifier();
        // Check if the user exists
        $user = $this->findUser($identifier);
        if (null === $user) {
            // Create the user
            $user = new User();
            $user->setUsername($identifier);
            $this->repository->save($user, true);
        }

        return $user;
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * @throws UserNotFoundException if the user no longer exists
     */
    #[\Override]
    public function refreshUser(UserInterface $user): User
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', get_class($user)));
        }

        $identifier = $user->getUserIdentifier();
        $user = $this->findUser($identifier);
        if (null === $user) {
            throw $this->createUserNotFoundException($identifier, sprintf('User with ID "%d" could not be reloaded.', $identifier));
        }

        return $user;
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    #[\Override]
    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    /**
     * Symfony calls this method if you use features like switch_user
     * or remember_me.
     *
     * @throws UserNotFoundException if the user is not found
     */
    #[\Override]
    public function loadUserByIdentifier(string $identifier): User
    {
        $user = $this->findUser($identifier);
        if (!$user) {
            throw $this->createUserNotFoundException($identifier, sprintf("User '%s' not found.", $identifier));
        }

        return $user;
    }

    /**
     * Find the user by its identifier (username).
     */
    private function findUser(string $identifier): ?User
    {
        return $this->repository->findOneBy([
            'username' => $identifier,
        ]);
    }

    /**
     * Generate and return a UserNotFoundException with the right parameters.
     */
    private function createUserNotFoundException(string $username, string $message): UserNotFoundException
    {
        $exception = new UserNotFoundException($message);
        $exception->setUserIdentifier($username);

        return $exception;
    }
}
