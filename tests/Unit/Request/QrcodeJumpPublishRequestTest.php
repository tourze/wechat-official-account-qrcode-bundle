<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpPublishRequest;

class QrcodeJumpPublishRequestTest extends TestCase
{
    private QrcodeJumpPublishRequest $request;

    protected function setUp(): void
    {
        $this->request = new QrcodeJumpPublishRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame(
            'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumppublish',
            $this->request->getRequestPath()
        );
    }

    public function testSetAndGetPrefix(): void
    {
        $prefix = 'http://weixin.qq.com/q/publish_test';
        $this->request->setPrefix($prefix);
        $this->assertSame($prefix, $this->request->getPrefix());
    }

    public function testGetRequestOptions(): void
    {
        $this->request->setPrefix('http://weixin.qq.com/q/publish_test');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        
        $json = $options['json'];
        $this->assertSame('http://weixin.qq.com/q/publish_test', $json['prefix']);
    }

    public function testDefaultValues(): void
    {
        $this->assertNull($this->request->getPrefix());
    }
}