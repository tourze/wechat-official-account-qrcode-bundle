<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tourze\BundleDependency\BundleDependencyInterface;
use WechatOfficialAccountQrcodeBundle\WechatOfficialAccountQrcodeBundle;

class WechatOfficialAccountQrcodeBundleTest extends TestCase
{
    private WechatOfficialAccountQrcodeBundle $bundle;

    protected function setUp(): void
    {
        $this->bundle = new WechatOfficialAccountQrcodeBundle();
    }

    public function testImplementsBundleDependencyInterface(): void
    {
        $this->assertInstanceOf(BundleDependencyInterface::class, $this->bundle);
    }

    public function testGetBundleDependencies(): void
    {
        $dependencies = WechatOfficialAccountQrcodeBundle::getBundleDependencies();
        
        $this->assertArrayHasKey(\Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle::class, $dependencies);
        $this->assertArrayHasKey(\WechatOfficialAccountBundle\WechatOfficialAccountBundle::class, $dependencies);
        
        $this->assertSame(['all' => true], $dependencies[\Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle::class]);
        $this->assertSame(['all' => true], $dependencies[\WechatOfficialAccountBundle\WechatOfficialAccountBundle::class]);
    }

    public function testGetName(): void
    {
        $this->assertSame('WechatOfficialAccountQrcodeBundle', $this->bundle->getName());
    }
}