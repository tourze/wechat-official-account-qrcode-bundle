<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Request;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpAddRequest;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpDeleteRequest;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpPublishRequest;

class QrcodeJumpRequestsTest extends TestCase
{
    public function testQrcodeJumpAddRequest(): void
    {
        $request = new QrcodeJumpAddRequest();
        
        // 测试请求路径
        $this->assertEquals('https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpadd', $request->getRequestPath());
        
        // 测试默认值
        $this->assertNull($request->getPrefix());
        $this->assertNull($request->getAppid());
        $this->assertNull($request->getPath());
        $this->assertNull($request->getIsEdit());
        
        // 测试设置属性
        $prefix = 'http://weixin.qq.com/q/abc123';
        $appid = 'wx1234567890abcdef';
        $path = 'pages/index/index';
        $isEdit = 0;
        
        $request->setPrefix($prefix);
        $request->setAppid($appid);
        $request->setPath($path);
        $request->setIsEdit($isEdit);
        
        $this->assertEquals($prefix, $request->getPrefix());
        $this->assertEquals($appid, $request->getAppid());
        $this->assertEquals($path, $request->getPath());
        $this->assertEquals($isEdit, $request->getIsEdit());
        
        // 测试请求选项
        $options = $request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('prefix', $options['json']);
        $this->assertArrayHasKey('appid', $options['json']);
        $this->assertArrayHasKey('path', $options['json']);
        $this->assertArrayHasKey('is_edit', $options['json']);
        $this->assertEquals($prefix, $options['json']['prefix']);
        $this->assertEquals($appid, $options['json']['appid']);
        $this->assertEquals($path, $options['json']['path']);
        $this->assertEquals($isEdit, $options['json']['is_edit']);
    }

    public function testQrcodeJumpDeleteRequest(): void
    {
        $request = new QrcodeJumpDeleteRequest();
        
        // 测试请求路径
        $this->assertEquals('https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdelete', $request->getRequestPath());
        
        // 测试默认值
        $this->assertNull($request->getPrefix());
        $this->assertNull($request->getAppid());
        
        // 测试设置属性
        $prefix = 'http://weixin.qq.com/q/abc123';
        $appid = 'wx1234567890abcdef';
        
        $request->setPrefix($prefix);
        $request->setAppid($appid);
        
        $this->assertEquals($prefix, $request->getPrefix());
        $this->assertEquals($appid, $request->getAppid());
        
        // 测试请求选项
        $options = $request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('prefix', $options['json']);
        $this->assertArrayHasKey('appid', $options['json']);
        $this->assertEquals($prefix, $options['json']['prefix']);
        $this->assertEquals($appid, $options['json']['appid']);
    }

    public function testQrcodeJumpPublishRequest(): void
    {
        $request = new QrcodeJumpPublishRequest();
        
        // 测试请求路径
        $this->assertEquals('https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumppublish', $request->getRequestPath());
        
        // 测试默认值
        $this->assertNull($request->getPrefix());
        
        // 测试设置属性
        $prefix = 'http://weixin.qq.com/q/abc123';
        $request->setPrefix($prefix);
        $this->assertEquals($prefix, $request->getPrefix());
        
        // 测试请求选项
        $options = $request->getRequestOptions();
        $this->assertIsArray($options);
        $this->assertArrayHasKey('json', $options);
        $this->assertArrayHasKey('prefix', $options['json']);
        $this->assertEquals($prefix, $options['json']['prefix']);
    }
} 