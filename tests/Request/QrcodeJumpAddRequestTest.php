<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Request;

use HttpClientBundle\Test\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpAddRequest;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpAddRequest::class)]
final class QrcodeJumpAddRequestTest extends RequestTestCase
{
    private QrcodeJumpAddRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new QrcodeJumpAddRequest();
    }

    public function testGetRequestPath(): void
    {
        $this->assertSame(
            'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpadd',
            $this->request->getRequestPath()
        );
    }

    public function testSetAndGetPrefix(): void
    {
        $prefix = 'http://weixin.qq.com/q/test';
        $this->request->setPrefix($prefix);
        $this->assertSame($prefix, $this->request->getPrefix());
    }

    public function testSetAndGetAppid(): void
    {
        $appid = 'test_appid_123';
        $this->request->setAppid($appid);
        $this->assertSame($appid, $this->request->getAppid());
    }

    public function testSetAndGetPath(): void
    {
        $path = 'pages/index/index';
        $this->request->setPath($path);
        $this->assertSame($path, $this->request->getPath());
    }

    public function testSetAndGetIsEdit(): void
    {
        $isEdit = 1;
        $this->request->setIsEdit($isEdit);
        $this->assertSame($isEdit, $this->request->getIsEdit());
    }

    public function testGetRequestOptions(): void
    {
        $this->request->setPrefix('http://weixin.qq.com/q/test');
        $this->request->setAppid('test_appid');
        $this->request->setPath('pages/test');
        $this->request->setIsEdit(0);

        $options = $this->request->getRequestOptions();

        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);

        $json = $options['json'];
        $this->assertIsArray($json);
        $this->assertSame('http://weixin.qq.com/q/test', $json['prefix']);
        $this->assertSame('test_appid', $json['appid']);
        $this->assertSame('pages/test', $json['path']);
        $this->assertSame(0, $json['is_edit']);
    }

    public function testDefaultValues(): void
    {
        $this->assertNull($this->request->getPrefix());
        $this->assertNull($this->request->getAppid());
        $this->assertNull($this->request->getPath());
        $this->assertNull($this->request->getIsEdit());
    }
}
