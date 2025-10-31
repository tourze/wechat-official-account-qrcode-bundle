<?php

namespace WechatOfficialAccountQrcodeBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

class QrcodeTicketFixtures extends Fixture
{
    public const QRCODE_TICKET_1_REFERENCE = 'qrcode-ticket-1';
    public const QRCODE_TICKET_2_REFERENCE = 'qrcode-ticket-2';

    public function load(ObjectManager $manager): void
    {
        $qrcodeTicket1 = new QrcodeTicket();
        $qrcodeTicket1->setValid(true);
        $qrcodeTicket1->setExpireTime(new \DateTimeImmutable('+30 days'));
        $qrcodeTicket1->setActionName(QrcodeActionName::QR_SCENE);
        $qrcodeTicket1->setSceneId(12345);
        $qrcodeTicket1->setTicket('test_ticket_123');
        $qrcodeTicket1->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test_ticket_123');

        $qrcodeTicket2 = new QrcodeTicket();
        $qrcodeTicket2->setValid(true);
        $qrcodeTicket2->setExpireTime(new \DateTimeImmutable('+7 days'));
        $qrcodeTicket2->setActionName(QrcodeActionName::QR_STR_SCENE);
        $qrcodeTicket2->setSceneStr('test_scene');
        $qrcodeTicket2->setTicket('test_ticket_456');
        $qrcodeTicket2->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test_ticket_456');

        $manager->persist($qrcodeTicket1);
        $manager->persist($qrcodeTicket2);

        $this->addReference(self::QRCODE_TICKET_1_REFERENCE, $qrcodeTicket1);
        $this->addReference(self::QRCODE_TICKET_2_REFERENCE, $qrcodeTicket2);

        $manager->flush();
    }
}
