<?php

namespace Edgar\EzUITreeMenuBundle\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use eZ\Publish\Core\MVC\ConfigResolverInterface;

class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM__BROWSE_TRE = 'sidebar_left__browse_tree';

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild(
            self::ITEM__BROWSE_TRE,
            [
                'extras' => ['icon' => 'copy-subtree'],
                'attributes' => [
                    'class' => 'ez-btn--extra-actions-left btn--tree-browse',
                    'data-actions' => 'browse-tree',
                ],
            ]
        );

        $children = $menu->getChildren();
        $order = array_keys($children);
        $oldPosition = array_search(self::ITEM__BROWSE_TRE, $order);
        unset($order[$oldPosition]);

        $order = array_values($order);

        array_splice($order, 1, 0, self::ITEM__BROWSE_TRE);
        $menu->reorderChildren($order);
    }

    /**
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM__BROWSE_TRE, 'messages'))->setDesc('Browse tree'),
        ];
    }
}
