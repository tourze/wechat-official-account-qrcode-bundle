<?php

namespace WechatOfficialAccountQrcodeBundle;

use AppBundle\Menu\LinkGenerator;
use Knp\Menu\ItemInterface;
use Tourze\EasyAdminMenuBundle\Attribute\MenuProvider;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;

#[MenuProvider]
class AdminMenu
{
    public function __construct(private readonly LinkGenerator $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (!$item->getChild('微信公众号')) {
            $item->addChild('微信公众号');
        }
        $item->getChild('微信公众号')->addChild('二维码管理')->setUri($this->linkGenerator->getCurdListPage(QrcodeTicket::class));
    }
}
