<?php

namespace App\EventSubscriber;

use KevinPapst\TablerBundle\Event\MenuEvent;
use KevinPapst\TablerBundle\Model\MenuItemInterface;
use KevinPapst\TablerBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class MenuBuilderSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private string $environment
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MenuEvent::class => ['onSetupNavbar', 100],
        ];
    }

    public function onSetupNavbar(MenuEvent $event): void
    {
        $event->addItem(
            new MenuItemModel('home', 'Accueil', 'admin_home', [], 'fas fa-home')
        );
        $event->addItem(
            new MenuItemModel('about_me', 'À propos', 'admin_about_me', [], 'fas fa-user')
        );
        $event->addItem(
            new MenuItemModel('projects', 'Projets', 'admin_projects', [], 'fas fa-briefcase')
        );
        $event->addItem(
            new MenuItemModel('socials', 'Réseaux', 'admin_socials', [], 'fas fa-share-nodes')
        );
        $event->addItem(
            new MenuItemModel('technologies', 'Technologies', 'admin_technologies', [], 'fas fa-tachometer-alt')
        );

        if ('dev' === $this->environment) {
            $toolbox = new MenuItemModel('toolbox', 'Toolbox', null, [], 'fas fa-tools');
            $toolbox->addChild(new MenuItemModel('Error 403', 'Error 403', '_preview_error', ['code' => '403'], 'fas fa-exclamation'));
            $toolbox->addChild(new MenuItemModel('Error 404', 'Error 404', '_preview_error', ['code' => '404'], 'fas fa-bug'));
            $toolbox->addChild(new MenuItemModel('Error 500', 'Error 500', '_preview_error', ['code' => '500'], 'fas fa-bomb'));
            $event->addItem($toolbox);
        }

        $this->activateByRoute(
            $event->getRequest(),
            $event->getItems()
        );
    }

    /**
     * @param MenuItemInterface[] $items
     */
    protected function activateByRoute(Request $request, array $items): void
    {
        foreach ($items as $item) {
            if ($item->hasChildren()) {
                $this->activateByRoute($request, $item->getChildren());
            } elseif ($item->getRoute() == $request->get('_route')) {
                $item->setIsActive(true);
            }
        }
    }
}
