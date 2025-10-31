<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpPublishRequest;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpPublishRequest::class)]
final class QrcodeJumpPublishRequestTest extends RequestTestCase
{
    private QrcodeJumpPublishRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

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
        $this->assertIsArray($json);
        $this->assertSame('http://weixin.qq.com/q/publish_test', $json['prefix']);
    }

    public function testDefaultValues(): void
    {
        $this->assertNull($this->request->getPrefix());
    }
}
