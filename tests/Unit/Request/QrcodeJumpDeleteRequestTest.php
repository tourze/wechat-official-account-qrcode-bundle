<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Unit\Request;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpDeleteRequest;

class QrcodeJumpDeleteRequestTest extends TestCase
{
    private QrcodeJumpDeleteRequest $request;

    protected function setUp(): void
    {
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
        $this->assertSame('http://weixin.qq.com/q/delete_test', $json['prefix']);
        $this->assertSame('delete_test_appid', $json['appid']);
    }

    public function testDefaultValues(): void
    {
        $this->assertNull($this->request->getPrefix());
        $this->assertNull($this->request->getAppid());
    }
}