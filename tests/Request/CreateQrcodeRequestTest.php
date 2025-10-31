<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Request;

use HttpClientBundle\Tests\Request\RequestTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;
use WechatOfficialAccountQrcodeBundle\Request\CreateQrcodeRequest;

/**
 * @internal
 */
#[CoversClass(CreateQrcodeRequest::class)]
final class CreateQrcodeRequestTest extends RequestTestCase
{
    private CreateQrcodeRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new CreateQrcodeRequest();
    }

    public function testDefaultValues(): void
    {
        $this->assertEquals(60, $this->request->getExpireSeconds());
        $this->assertEquals(QrcodeActionName::QR_STR_SCENE, $this->request->getActionName());
        $this->assertNull($this->request->getSceneId());
        $this->assertNull($this->request->getSceneStr());
    }

    public function testGetRequestPath(): void
    {
        $this->assertEquals('https://api.weixin.qq.com/cgi-bin/qrcode/create', $this->request->getRequestPath());
    }

    public function testExpireSeconds(): void
    {
        $expireSeconds = 3600;
        $this->request->setExpireSeconds($expireSeconds);
        $this->assertEquals($expireSeconds, $this->request->getExpireSeconds());
    }

    public function testActionName(): void
    {
        $actionName = QrcodeActionName::QR_LIMIT_SCENE;
        $this->request->setActionName($actionName);
        $this->assertEquals($actionName, $this->request->getActionName());
    }

    public function testSceneId(): void
    {
        $sceneId = 12345;
        $this->request->setSceneId($sceneId);
        $this->assertEquals($sceneId, $this->request->getSceneId());
    }

    public function testSceneStr(): void
    {
        $sceneStr = 'test_scene';
        $this->request->setSceneStr($sceneStr);
        $this->assertEquals($sceneStr, $this->request->getSceneStr());
    }

    public function testGetRequestOptionsWithStrScene(): void
    {
        $this->request->setActionName(QrcodeActionName::QR_STR_SCENE);
        $this->request->setSceneStr('test_scene');

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('expire_seconds', $options['json']);
        $this->assertArrayHasKey('action_name', $options['json']);
        $this->assertArrayHasKey('action_info', $options['json']);
        $this->assertIsArray($options['json']['action_info']);
        $this->assertArrayHasKey('scene', $options['json']['action_info']);
        $this->assertIsArray($options['json']['action_info']['scene']);
        $this->assertArrayHasKey('scene_str', $options['json']['action_info']['scene']);
        $this->assertEquals('test_scene', $options['json']['action_info']['scene']['scene_str']);
        $this->assertSame(QrcodeActionName::QR_STR_SCENE, $options['json']['action_name']);
    }

    public function testGetRequestOptionsWithIdScene(): void
    {
        $this->request->setActionName(QrcodeActionName::QR_SCENE);
        $this->request->setSceneId(12345);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('action_info', $options['json']);
        $this->assertIsArray($options['json']['action_info']);
        $this->assertArrayHasKey('scene', $options['json']['action_info']);
        $this->assertIsArray($options['json']['action_info']['scene']);
        $this->assertArrayHasKey('scene_id', $options['json']['action_info']['scene']);
        $this->assertEquals(12345, $options['json']['action_info']['scene']['scene_id']);
        $this->assertSame(QrcodeActionName::QR_SCENE, $options['json']['action_name']);
    }

    public function testGetRequestOptionsWithLimitStrScene(): void
    {
        $this->request->setActionName(QrcodeActionName::QR_LIMIT_STR_SCENE);
        $this->request->setSceneStr('permanent_scene');

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('action_info', $options['json']);
        $this->assertIsArray($options['json']['action_info']);
        $this->assertArrayHasKey('scene', $options['json']['action_info']);
        $this->assertIsArray($options['json']['action_info']['scene']);
        $this->assertArrayHasKey('scene_str', $options['json']['action_info']['scene']);
        $this->assertEquals('permanent_scene', $options['json']['action_info']['scene']['scene_str']);
        $this->assertSame(QrcodeActionName::QR_LIMIT_STR_SCENE, $options['json']['action_name']);
    }

    public function testGetRequestOptionsWithLimitScene(): void
    {
        $this->request->setActionName(QrcodeActionName::QR_LIMIT_SCENE);
        $this->request->setSceneId(1024);

        $options = $this->request->getRequestOptions();
        $this->assertArrayHasKey('json', $options);
        $this->assertIsArray($options['json']);
        $this->assertArrayHasKey('action_info', $options['json']);
        $this->assertIsArray($options['json']['action_info']);
        $this->assertArrayHasKey('scene', $options['json']['action_info']);
        $this->assertIsArray($options['json']['action_info']['scene']);
        $this->assertArrayHasKey('scene_id', $options['json']['action_info']['scene']);
        $this->assertEquals(1024, $options['json']['action_info']['scene']['scene_id']);
        $this->assertSame(QrcodeActionName::QR_LIMIT_SCENE, $options['json']['action_name']);
    }

    public function testSetAccount(): void
    {
        $account = new Account();
        $this->request->setAccount($account);

        $this->assertSame($account, $this->request->getAccount());
    }
}
