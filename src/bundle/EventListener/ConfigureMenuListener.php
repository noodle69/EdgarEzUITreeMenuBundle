<?php

namespace Edgar\EzUITreeMenuBundle\EventListener;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

class ConfigureMenuListener implements TranslationContainerInterface
{
    /** @var ConfigResolverInterface  */
    private $configResolver;

    const ITEM__BROWSE_TRE = 'sidebar_left__browse_tree';

    public function __construct(
        ConfigResolverInterface $configResolver
    ) {
        $this->configResolver = $configResolver;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild(
            self::ITEM__BROWSE_TRE,
            [
                'extras' => ['icon' => 'list'],
                'attributes' => [
                    'class' => 'ez-btn--extra-actions btn--tree-browse',
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