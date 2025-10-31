<?php

namespace WechatOfficialAccountQrcodeBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;

class ScanLogFixtures extends Fixture implements DependentFixtureInterface
{
    public const SCAN_LOG_1_REFERENCE = 'scan-log-1';
    public const SCAN_LOG_2_REFERENCE = 'scan-log-2';

    public function load(ObjectManager $manager): void
    {
        $qrcodeTicket1 = $this->getReference(QrcodeTicketFixtures::QRCODE_TICKET_1_REFERENCE, QrcodeTicket::class);
        assert($qrcodeTicket1 instanceof QrcodeTicket);

        $scanLog1 = new ScanLog();
        $scanLog1->setQrcode($qrcodeTicket1);
        $scanLog1->setOpenId('test_openid_123');

        $scanLog2 = new ScanLog();
        $scanLog2->setQrcode($qrcodeTicket1);
        $scanLog2->setOpenId('test_openid_456');

        $manager->persist($scanLog1);
        $manager->persist($scanLog2);

        $this->addReference(self::SCAN_LOG_1_REFERENCE, $scanLog1);
        $this->addReference(self::SCAN_LOG_2_REFERENCE, $scanLog2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            QrcodeTicketFixtures::class,
        ];
    }
}
