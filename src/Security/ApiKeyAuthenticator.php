<?php

namespace App\Security;

use App\Entity\ApiKey;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Uid\Uuid;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly EntityManagerInterface $em
    ) {
    }

    public function supports(Request $request): ?bool
    {
        // Check if the incoming HTTP request lacks the 'api_key' parameter
        return $request->query->has('api_key');
    }

    public function authenticate(Request $request): Passport
    {
        $api_key = $request->query->get('api_key');
        // Check if an API Token is provided
        if (empty($api_key)) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        // Check if the extracted API Key is a valid UUID
        if (!Uuid::isValid($api_key)) {
            throw new HttpException(401, 'Your API Key is not valid.');
        }

        // Search if the API Key exists
        $key = $this->em->getRepository(ApiKey::class)->findOneBy(['key' => $api_key]);
        if (empty($key)) {
            throw new HttpException(401, 'Your API Key is not valid.');
        }

        // Check if the key is not expired
        if (null !== $key->getExpirationDate() && $key->getExpirationDate() < new \DateTime()) {
            throw new HttpException(401, 'Your API Key is expired.');
        }

        // Check if there is an account related to this api key
        $user = $key->getAccount();
        if (null === $user) {
            throw new HttpException(401, 'Your API Key is not valid.');
        }

        // Update the last accessed property
        $this->em->getRepository(ApiKey::class)->updateLastAccessed($key);

        return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'status' => Response::HTTP_UNAUTHORIZED,
            'message' => $exception->getMessage(),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
