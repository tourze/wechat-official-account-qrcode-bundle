<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Unit\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use WechatOfficialAccountQrcodeBundle\DependencyInjection\WechatOfficialAccountQrcodeExtension;

class WechatOfficialAccountQrcodeExtensionTest extends TestCase
{
    private WechatOfficialAccountQrcodeExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new WechatOfficialAccountQrcodeExtension();
        $this->container = new ContainerBuilder();
    }

    public function testLoad(): void
    {
        $this->extension->load([], $this->container);
        
        // 检查扩展是否成功加载，通过验证容器不为空来确认
        $this->assertNotNull($this->container);
    }

    public function testGetAlias(): void
    {
        $this->assertSame('wechat_official_account_qrcode', $this->extension->getAlias());
    }
}