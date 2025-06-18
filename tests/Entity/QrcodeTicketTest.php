<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Entity;

use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

class QrcodeTicketTest extends TestCase
{
    private QrcodeTicket $qrcodeTicket;

    protected function setUp(): void
    {
        $this->qrcodeTicket = new QrcodeTicket();
    }

    public function testId(): void
    {
        // 由于ID是自动生成的，这里仅测试初始值
        $this->assertEquals(0, $this->qrcodeTicket->getId());
    }

    public function testCreateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->qrcodeTicket->setCreateTime($date);
        $this->assertSame($date, $this->qrcodeTicket->getCreateTime());
    }

    public function testUpdateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->qrcodeTicket->setUpdateTime($date);
        $this->assertSame($date, $this->qrcodeTicket->getUpdateTime());
    }

    public function testValid(): void
    {
        $this->assertFalse($this->qrcodeTicket->isValid()); // 默认值为false
        
        $this->qrcodeTicket->setValid(true);
        $this->assertTrue($this->qrcodeTicket->isValid());
        
        $this->qrcodeTicket->setValid(false);
        $this->assertFalse($this->qrcodeTicket->isValid());
    }

    public function testAccount(): void
    {
        $account = new Account();
        $this->qrcodeTicket->setAccount($account);
        $this->assertSame($account, $this->qrcodeTicket->getAccount());
        
        $this->qrcodeTicket->setAccount(null);
        $this->assertNull($this->qrcodeTicket->getAccount());
    }

    public function testExpireTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->qrcodeTicket->setExpireTime($date);
        $this->assertSame($date, $this->qrcodeTicket->getExpireTime());
    }

    public function testActionName(): void
    {
        $actionName = QrcodeActionName::QR_SCENE;
        $this->qrcodeTicket->setActionName($actionName);
        $this->assertSame($actionName, $this->qrcodeTicket->getActionName());
        
        $actionName = QrcodeActionName::QR_LIMIT_STR_SCENE;
        $this->qrcodeTicket->setActionName($actionName);
        $this->assertSame($actionName, $this->qrcodeTicket->getActionName());
    }

    public function testSceneId(): void
    {
        $sceneId = 12345;
        $this->qrcodeTicket->setSceneId($sceneId);
        $this->assertEquals($sceneId, $this->qrcodeTicket->getSceneId());
        
        $this->qrcodeTicket->setSceneId(null);
        $this->assertNull($this->qrcodeTicket->getSceneId());
    }

    public function testSceneStr(): void
    {
        $sceneStr = 'test_scene';
        $this->qrcodeTicket->setSceneStr($sceneStr);
        $this->assertEquals($sceneStr, $this->qrcodeTicket->getSceneStr());
        
        $this->qrcodeTicket->setSceneStr(null);
        $this->assertNull($this->qrcodeTicket->getSceneStr());
    }

    public function testTicket(): void
    {
        $ticket = 'gQH47joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2taZ2Z3TVRtNzJXV1Brb3ZhYmJJAAIEZ23sUwMEmm3sUw==';
        $this->qrcodeTicket->setTicket($ticket);
        $this->assertEquals($ticket, $this->qrcodeTicket->getTicket());
        
        $this->qrcodeTicket->setTicket(null);
        $this->assertNull($this->qrcodeTicket->getTicket());
    }

    public function testUrl(): void
    {
        $url = 'http://weixin.qq.com/q/kZgfwMTm72WWPkovabbI';
        $this->qrcodeTicket->setUrl($url);
        $this->assertEquals($url, $this->qrcodeTicket->getUrl());
        
        $this->qrcodeTicket->setUrl(null);
        $this->assertNull($this->qrcodeTicket->getUrl());
    }

    public function testScanLogs(): void
    {
        // 测试初始集合是否为空
        $this->assertInstanceOf(Collection::class, $this->qrcodeTicket->getScanLogs());
        $this->assertTrue($this->qrcodeTicket->getScanLogs()->isEmpty());
        
        // 测试添加ScanLog
        $scanLog = new ScanLog();
        $this->qrcodeTicket->addScanLog($scanLog);
        $this->assertCount(1, $this->qrcodeTicket->getScanLogs());
        $this->assertTrue($this->qrcodeTicket->getScanLogs()->contains($scanLog));
        
        // 测试重复添加相同的ScanLog不会导致重复
        $this->qrcodeTicket->addScanLog($scanLog);
        $this->assertCount(1, $this->qrcodeTicket->getScanLogs());
        
        // 测试添加多个ScanLog
        $scanLog2 = new ScanLog();
        $this->qrcodeTicket->addScanLog($scanLog2);
        $this->assertCount(2, $this->qrcodeTicket->getScanLogs());
        
        // 测试移除ScanLog
        $this->qrcodeTicket->removeScanLog($scanLog);
        $this->assertCount(1, $this->qrcodeTicket->getScanLogs());
        $this->assertFalse($this->qrcodeTicket->getScanLogs()->contains($scanLog));
        $this->assertTrue($this->qrcodeTicket->getScanLogs()->contains($scanLog2));
        
        // 测试移除不存在的ScanLog
        $scanLog3 = new ScanLog();
        $this->qrcodeTicket->removeScanLog($scanLog3);
        $this->assertCount(1, $this->qrcodeTicket->getScanLogs());
    }
} 