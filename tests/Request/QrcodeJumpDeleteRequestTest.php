<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpDeleteRequest;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpDeleteRequest::class)]
final class QrcodeJumpDeleteRequestTest extends RequestTestCase
{
    private QrcodeJumpDeleteRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new QrcodeJumpDeleteRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame(
            'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdelete',
            $this->request->getRequestPath()
        );
    }

    public function testSetAndGetPrefix(): void
    {
        $prefix = 'http://weixin.qq.com/q/delete_test';
        $this->request->setPrefix($prefix);
        $this->assertSame($prefix, $this->request->getPrefix());
    }

    public function testSetAndGetAppid(): void
    {
        $appid = 'delete_test_appid_123';
        $this->request->setAppid($appid);
        $this->assertSame($appid, $this->request->getAppid());
    }

    public function testGetRequestOptions(): void
    {
        $this->request->setPrefix('http://weixin.qq.com/q/delete_test');
        $this->request->setAppid('delete_test_appid');

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertSame('http://weixin.qq.com/q/delete_test', $json['prefix']);
        $this->assertSame('delete_test_appid', $json['appid']);
    }

    public function testDefaultValues(): void
    {
        $this->assertNull($this->request->getPrefix());
        $this->assertNull($this->request->getAppid());
    }
}
