<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;

/**
 * @internal
 */
#[CoversClass(QrcodeJump::class)]
final class QrcodeJumpTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new QrcodeJump();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            'prefix' => ['prefix', 'test_value'],
            'appid' => ['appid', 'test_value'],
            'path' => ['path', 'test_value'],
            'edit' => ['edit', 123],
            'state' => ['state', 123],
        ];
    }

    private QrcodeJump $qrcodeJump;

    protected function setUp(): void
    {
        parent::setUp();

        $this->qrcodeJump = new QrcodeJump();
    }

    public function testId(): void
    {
        // ID是在保存到数据库时由Snowflake生成的，初始应为null
        $this->assertNull($this->qrcodeJump->getId());
    }

    public function testPrefix(): void
    {
        $prefix = 'http://weixin.qq.com/q/abcdefg123456';
        $this->qrcodeJump->setPrefix($prefix);
        $this->assertEquals($prefix, $this->qrcodeJump->getPrefix());

        $prefix2 = 'http://weixin.qq.com/q/xyz789';
        $this->qrcodeJump->setPrefix($prefix2);
        $this->assertEquals($prefix2, $this->qrcodeJump->getPrefix());
    }

    public function testAppid(): void
    {
        $appid = 'wx1234567890abcdef';
        $this->qrcodeJump->setAppid($appid);
        $this->assertEquals($appid, $this->qrcodeJump->getAppid());

        $appid2 = 'wxabcdef1234567890';
        $this->qrcodeJump->setAppid($appid2);
        $this->assertEquals($appid2, $this->qrcodeJump->getAppid());
    }

    public function testPath(): void
    {
        $path = 'pages/index/index';
        $this->qrcodeJump->setPath($path);
        $this->assertEquals($path, $this->qrcodeJump->getPath());

        $path2 = 'pages/user/profile?id=123';
        $this->qrcodeJump->setPath($path2);
        $this->assertEquals($path2, $this->qrcodeJump->getPath());
    }

    public function testEdit(): void
    {
        $edit = 0; // 新增二维码规则
        $this->qrcodeJump->setEdit($edit);
        $this->assertEquals($edit, $this->qrcodeJump->getEdit());

        $edit2 = 1; // 修改已有二维码规则
        $this->qrcodeJump->setEdit($edit2);
        $this->assertEquals($edit2, $this->qrcodeJump->getEdit());
    }

    public function testState(): void
    {
        // 检查默认值
        $this->assertEquals(0, $this->qrcodeJump->getState());

        $state = 1; // 已发布
        $this->qrcodeJump->setState($state);
        $this->assertEquals($state, $this->qrcodeJump->getState());

        $state2 = 0; // 未发布
        $this->qrcodeJump->setState($state2);
        $this->assertEquals($state2, $this->qrcodeJump->getState());
    }

    public function testCreateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->qrcodeJump->setCreateTime($date);
        $this->assertSame($date, $this->qrcodeJump->getCreateTime());

        $date2 = new \DateTimeImmutable('+1 day');
        $this->qrcodeJump->setCreateTime($date2);
        $this->assertSame($date2, $this->qrcodeJump->getCreateTime());
        $this->assertNotSame($date, $this->qrcodeJump->getCreateTime());

        $this->qrcodeJump->setCreateTime(null);
        $this->assertNull($this->qrcodeJump->getCreateTime());
    }

    public function testUpdateTime(): void
    {
        $date = new \DateTimeImmutable();
        $this->qrcodeJump->setUpdateTime($date);
        $this->assertSame($date, $this->qrcodeJump->getUpdateTime());

        $date2 = new \DateTimeImmutable('+1 day');
        $this->qrcodeJump->setUpdateTime($date2);
        $this->assertSame($date2, $this->qrcodeJump->getUpdateTime());
        $this->assertNotSame($date, $this->qrcodeJump->getUpdateTime());

        $this->qrcodeJump->setUpdateTime(null);
        $this->assertNull($this->qrcodeJump->getUpdateTime());
    }
}
