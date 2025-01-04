<?php

namespace App\EventSubscriber;

use App\Repository\AboutMeRepository;
use KevinPapst\TablerBundle\Event\UserDetailsEvent;
use KevinPapst\TablerBundle\Model\MenuItemModel;
use KevinPapst\TablerBundle\Model\UserModel;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\User\InMemoryUser;

readonly class UserDetailsSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private AboutMeRepository $aboutMeRepository,
        private string $assetsUrl,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserDetailsEvent::class => ['onShowUser', 100],
        ];
    }

    public function onShowUser(UserDetailsEvent $event): void
    {
        if (null === $this->security->getUser()) {
            return;
        }

        /* @var $myUser InMemoryUser */
        $myUser = $this->security->getUser();

        $about_me = $this->aboutMeRepository->findOneBy([]);

        $user = new UserModel('1', $myUser->getUserIdentifier());
        $user->setName($about_me?->getName() ?? $myUser->getUserIdentifier());
        $avatar = $about_me?->getPicture()?->getFileName();
        if ($avatar) {
            $user->setAvatar($this->assetsUrl.'/'.$avatar);
        }
        $event->setUser($user);

        $event->addLink(new MenuItemModel('profile', $user->getName(), 'admin_about_me'));
        $event->addLink(new MenuItemModel('api-keys', 'API Keys', 'admin_api_keys'));
    }
}
