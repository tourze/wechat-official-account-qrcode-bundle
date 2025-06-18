<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\WechatOfficialAccountContracts\UserInterface;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;

class ScanLogTest extends TestCase
{
    private ScanLog $scanLog;

    protected function setUp(): void
    {
        $this->scanLog = new ScanLog();
    }

    public function testId(): void
    {
        // 由于ID是自动生成的，这里仅测试初始值
        $this->assertEquals(0, $this->scanLog->getId());
    }

    public function testQrcode(): void
    {
        $qrcodeTicket = new QrcodeTicket();
        $this->scanLog->setQrcode($qrcodeTicket);
        $this->assertSame($qrcodeTicket, $this->scanLog->getQrcode());

        $qrcodeTicket2 = new QrcodeTicket();
        $this->scanLog->setQrcode($qrcodeTicket2);
        $this->assertSame($qrcodeTicket2, $this->scanLog->getQrcode());
        $this->assertNotSame($qrcodeTicket, $this->scanLog->getQrcode());
    }

    public function testOpenId(): void
    {
        $openId = 'o6_bmjrPTlm6_2sgVt7hMZOPfL2M';
        $this->scanLog->setOpenId($openId);
        $this->assertEquals($openId, $this->scanLog->getOpenId());

        $newOpenId = 'o6_bmjrPTlm6_2sgVt7hMZOPfL3M';
        $this->scanLog->setOpenId($newOpenId);
        $this->assertEquals($newOpenId, $this->scanLog->getOpenId());
        $this->assertNotEquals($openId, $this->scanLog->getOpenId());
    }

    public function testUser(): void
    {
        $user = $this->createMock(UserInterface::class);
        $this->scanLog->setUser($user);
        $this->assertSame($user, $this->scanLog->getUser());

        $user2 = $this->createMock(UserInterface::class);
        $this->scanLog->setUser($user2);
        $this->assertSame($user2, $this->scanLog->getUser());
        $this->assertNotSame($user, $this->scanLog->getUser());

        $this->scanLog->setUser(null);
        $this->assertNull($this->scanLog->getUser());
    }

    public function testCreateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->scanLog->setCreateTime($date);
        $this->assertSame($date, $this->scanLog->getCreateTime());

        $date2 = new \DateTimeImmutable('+1 day');
        $this->scanLog->setCreateTime($date2);
        $this->assertSame($date2, $this->scanLog->getCreateTime());
        $this->assertNotSame($date, $this->scanLog->getCreateTime());

        $this->scanLog->setCreateTime(null);
        $this->assertNull($this->scanLog->getCreateTime());
    }

    public function testMethodChaining(): void
    {
        $qrcodeTicket = new QrcodeTicket();
        $openId = 'o6_bmjrPTlm6_2sgVt7hMZOPfL2M';
        $user = $this->createMock(UserInterface::class);
        $date = new \DateTimeImmutable();

        $result = $this->scanLog
            ->setQrcode($qrcodeTicket)
            ->setOpenId($openId)
            ->setUser($user)
            ->setCreateTime($date);

        $this->assertSame($this->scanLog, $result);
        $this->assertSame($qrcodeTicket, $this->scanLog->getQrcode());
        $this->assertEquals($openId, $this->scanLog->getOpenId());
        $this->assertSame($user, $this->scanLog->getUser());
        $this->assertSame($date, $this->scanLog->getCreateTime());
    }
}
