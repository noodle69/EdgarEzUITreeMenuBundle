<?php

namespace Edgar\EzUITreeMenuBundle\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ConfigureMenuListener implements TranslationContainerInterface
{
    const ITEM__BROWSE_TRE = 'sidebar_left__browse_tree';

    /** @var RequestStack */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->attributes->has('locationId')) {
            $menu = $event->getMenu();

            $menu->addChild(
                self::ITEM__BROWSE_TRE,
                [
                    'extras' => ['icon' => 'list'],
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
