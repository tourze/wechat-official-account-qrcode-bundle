<?php

namespace WechatOfficialAccountQrcodeBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;

class QrcodeJumpFixtures extends Fixture
{
    public const QRCODE_JUMP_1_REFERENCE = 'qrcode-jump-1';
    public const QRCODE_JUMP_2_REFERENCE = 'qrcode-jump-2';

    public function load(ObjectManager $manager): void
    {
        $qrcodeJump1 = new QrcodeJump();
        $qrcodeJump1->setPrefix('http://weixin.qq.com/q/test1');
        $qrcodeJump1->setAppid('wx1234567890abcdef');
        $qrcodeJump1->setPath('/pages/index');
        $qrcodeJump1->setEdit(0);
        $qrcodeJump1->setState(1);

        $qrcodeJump2 = new QrcodeJump();
        $qrcodeJump2->setPrefix('http://weixin.qq.com/q/test2');
        $qrcodeJump2->setAppid('wx0987654321fedcba');
        $qrcodeJump2->setPath('/pages/product');
        $qrcodeJump2->setEdit(1);
        $qrcodeJump2->setState(0);

        $manager->persist($qrcodeJump1);
        $manager->persist($qrcodeJump2);

        $this->addReference(self::QRCODE_JUMP_1_REFERENCE, $qrcodeJump1);
        $this->addReference(self::QRCODE_JUMP_2_REFERENCE, $qrcodeJump2);

        $manager->flush();
    }
}
