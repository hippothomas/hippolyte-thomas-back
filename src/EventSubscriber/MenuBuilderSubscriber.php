<?php

namespace App\EventSubscriber;

use KevinPapst\TablerBundle\Event\MenuEvent;
use KevinPapst\TablerBundle\Model\MenuItemInterface;
use KevinPapst\TablerBundle\Model\MenuItemModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;

readonly class MenuBuilderSubscriber implements EventSubscriberInterface
{
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

        // Articles
        $article = new MenuItemModel('posts-menu', 'Articles', null, [], 'fas fa-pen-to-square');
        $article->addChild(new MenuItemModel('new-post', 'Nouvel article', 'admin_post_new', [], 'fas fa-plus'));
        $article->addChild(new MenuItemModel('posts', 'Liste des Articles', 'admin_posts', [], 'fas fa-bars-staggered'));
        $article->addChild(new MenuItemModel('tags', 'Tags', 'admin_tags', [], 'fas fa-tag'));
        $event->addItem($article);

        // Projets
        $projets = new MenuItemModel('projects-menu', 'Projets', null, [], 'fas fa-briefcase');
        $projets->addChild(new MenuItemModel('new-project', 'Nouveau projet', 'admin_project_new', [], 'fas fa-plus'));
        $projets->addChild(new MenuItemModel('projects', 'Liste des Projets', 'admin_projects', [], 'fas fa-bars-staggered'));
        $projets->addChild(new MenuItemModel('technologies', 'Technologies', 'admin_technologies', [], 'fas fa-tachometer-alt'));
        $event->addItem($projets);

        $event->addItem(
            new MenuItemModel('socials', 'Réseaux', 'admin_socials', [], 'fas fa-share-nodes')
        );
        $event->addItem(
            new MenuItemModel('about_me', 'À propos', 'admin_about_me', [], 'fas fa-user')
        );

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
