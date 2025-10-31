<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Service;

use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatOfficialAccountQrcodeBundle\Controller\Admin\QrcodeJumpCrudController;
use WechatOfficialAccountQrcodeBundle\Controller\Admin\QrcodeTicketCrudController;
use WechatOfficialAccountQrcodeBundle\Controller\Admin\ScanLogCrudController;

class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    /** @return array<array{label: string, icon: string, submenu?: array<array{label: string, icon: string, controller: class-string, permission: string}>}> */
    public function getMenuItems(): array
    {
        return [
            [
                'label' => '微信公众号二维码',
                'icon' => 'fas fa-qrcode',
                'submenu' => [
                    [
                        'label' => '二维码规则',
                        'icon' => 'fas fa-cogs',
                        'controller' => QrcodeJumpCrudController::class,
                        'permission' => 'ROLE_ADMIN',
                    ],
                    [
                        'label' => '二维码Ticket',
                        'icon' => 'fas fa-ticket-alt',
                        'controller' => QrcodeTicketCrudController::class,
                        'permission' => 'ROLE_ADMIN',
                    ],
                    [
                        'label' => '扫描记录',
                        'icon' => 'fas fa-history',
                        'controller' => ScanLogCrudController::class,
                        'permission' => 'ROLE_ADMIN',
                    ],
                ],
            ],
        ];
    }

    /** @return array<array{entity: string, label: string, icon: string}> */
    public function getMenuItemsForEasyAdmin(): array
    {
        $items = [];

        foreach ($this->getMenuItems() as $menuGroup) {
            if (isset($menuGroup['submenu'])) {
                foreach ($menuGroup['submenu'] as $item) {
                    $items[] = [
                        'entity' => $item['controller']::getEntityFqcn(),
                        'label' => $item['label'],
                        'icon' => $item['icon'],
                    ];
                }
            } elseif (isset($menuGroup['controller'])) {
                $items[] = [
                    'entity' => $menuGroup['controller']::getEntityFqcn(),
                    'label' => $menuGroup['label'],
                    'icon' => $menuGroup['icon'],
                ];
            }
        }

        return $items;
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('微信管理')) {
            $item->addChild('微信管理');
        }

        $wechatMenu = $item->getChild('微信管理');
        if (null === $wechatMenu) {
            return;
        }

        // 添加微信公众号二维码子菜单
        if (null === $wechatMenu->getChild('微信公众号二维码')) {
            $wechatMenu->addChild('微信公众号二维码')
                ->setAttribute('icon', 'fas fa-qrcode')
            ;
        }

        $qrcodeMenu = $wechatMenu->getChild('微信公众号二维码');
        if (null === $qrcodeMenu) {
            return;
        }

        $qrcodeMenu->addChild('二维码规则')
            ->setUri($this->linkGenerator->getCurdListPage(QrcodeJumpCrudController::class))
            ->setAttribute('icon', 'fas fa-cogs')
        ;

        $qrcodeMenu->addChild('二维码Ticket')
            ->setUri($this->linkGenerator->getCurdListPage(QrcodeTicketCrudController::class))
            ->setAttribute('icon', 'fas fa-ticket-alt')
        ;

        $qrcodeMenu->addChild('扫描记录')
            ->setUri($this->linkGenerator->getCurdListPage(ScanLogCrudController::class))
            ->setAttribute('icon', 'fas fa-history')
        ;
    }
}
