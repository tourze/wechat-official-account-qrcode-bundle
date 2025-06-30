<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\EventSubscriber;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Exception\ApiException;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;
use WechatOfficialAccountQrcodeBundle\EventSubscriber\QrcodeJumpListener;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpAddRequest;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpDeleteRequest;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpPublishRequest;

class QrcodeJumpListenerTest extends TestCase
{
    private OfficialAccountClient $client;
    private LoggerInterface $logger;
    private QrcodeJumpListener $listener;

    protected function setUp(): void
    {
        $this->client = $this->createMock(OfficialAccountClient::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->listener = new QrcodeJumpListener($this->client, $this->logger);
    }

    public function testPrePersistWithValidData(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0); // 新增二维码规则
        $qrcodeJump->setState(0); // 未发布状态
        
        // 设置 client 的预期行为 - 首先处理添加请求
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with($this->callback(function (QrcodeJumpAddRequest $request) use ($qrcodeJump) {
                return $request->getPrefix() === $qrcodeJump->getPrefix()
                    && $request->getPath() === $qrcodeJump->getPath()
                    && $request->getAppid() === $qrcodeJump->getAppid()
                    && $request->getIsEdit() === $qrcodeJump->getEdit();
            }))
            ->willReturn(['errcode' => 0, 'errmsg' => 'ok']);
        
        // 执行被测试的方法
        $this->listener->prePersist($qrcodeJump);
    }

    public function testPrePersistWithValidDataAndPublished(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0); // 新增二维码规则
        $qrcodeJump->setState(1); // 已发布状态
        
        // 首先，我们期望client->request被调用两次
        // 第一次是为了添加请求
        $this->client
            ->expects($this->exactly(2))
            ->method('request')
            ->willReturnCallback(function ($request) use ($qrcodeJump) {
                if ($request instanceof QrcodeJumpAddRequest) {
                    $this->assertSame($qrcodeJump->getPrefix(), $request->getPrefix());
                    $this->assertSame($qrcodeJump->getPath(), $request->getPath());
                    $this->assertSame($qrcodeJump->getAppid(), $request->getAppid());
                    $this->assertSame($qrcodeJump->getEdit(), $request->getIsEdit());
                    return ['errcode' => 0, 'errmsg' => 'ok'];
                } elseif ($request instanceof QrcodeJumpPublishRequest) {
                    $this->assertSame($qrcodeJump->getPrefix(), $request->getPrefix());
                    return ['errcode' => 0, 'errmsg' => 'ok'];
                }
                $this->fail('Unexpected request type: ' . get_class($request));
            });
        
        // 执行被测试的方法
        $this->listener->prePersist($qrcodeJump);
    }

    public function testPrePersistWithEmptyPrefix(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix(''); // 空前缀
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0);
        
        // 空前缀应该抛出异常
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('prefix不能为空');
        
        // client 的请求方法不应被调用
        $this->client->expects($this->never())->method('request');
        
        // 执行被测试的方法
        $this->listener->prePersist($qrcodeJump);
    }

    public function testPrePersistWithClientError(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0);
        $qrcodeJump->setState(0);
        
        // 设置 client 抛出异常
        $exception = new \Exception('API request failed');
        $this->client
            ->expects($this->once())
            ->method('request')
            ->willThrowException($exception);
        
        // 客户端错误应该被包装为 ApiException
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('API request failed');
        
        // 执行被测试的方法
        $this->listener->prePersist($qrcodeJump);
    }

    public function testPreUpdateFromUnpublishedToPublished(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setState(1); // 设置为已发布状态
        
        // 创建 PreUpdateEventArgs mock
        $oldValues = ['state' => 0]; // 旧状态为未发布
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getOldValue')
            ->with('state')
            ->willReturn(0);
        
        // 设置 client 的预期行为 - 应调用发布请求
        $this->client
            ->expects($this->once())
            ->method('request')
            ->with($this->callback(function (QrcodeJumpPublishRequest $request) use ($qrcodeJump) {
                return $request->getPrefix() === $qrcodeJump->getPrefix();
            }))
            ->willReturn(['errcode' => 0, 'errmsg' => 'ok']);
        
        // 执行被测试的方法
        $this->listener->preUpdate($qrcodeJump, $eventArgs);
    }

    public function testPreUpdateFromPublishedToUnpublished(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setState(0); // 设置为未发布状态
        
        // 创建 PreUpdateEventArgs mock
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getOldValue')
            ->with('state')
            ->willReturn(1); // 旧状态为已发布
        
        // 从已发布改为未发布应该抛出异常
        $this->expectException(ApiException::class);
        $this->expectExceptionMessage('已发布规则不允许修改');
        
        // client 的请求方法不应被调用
        $this->client->expects($this->never())->method('request');
        
        // 执行被测试的方法
        $this->listener->preUpdate($qrcodeJump, $eventArgs);
    }

    public function testPreRemove(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        
        // 设置 client 的预期行为 - 应调用删除请求
        $this->client
            ->expects($this->once())
            ->method('asyncRequest')
            ->with($this->callback(function (QrcodeJumpDeleteRequest $request) use ($qrcodeJump) {
                return $request->getPrefix() === $qrcodeJump->getPrefix()
                    && $request->getAppid() === $qrcodeJump->getAppid();
            }));
        
        // 执行被测试的方法
        $this->listener->preRemove($qrcodeJump);
    }

    public function testPreRemoveWithError(): void
    {
        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        
        // 设置 client 抛出异常
        $exception = new \Exception('Delete request failed');
        $this->client
            ->expects($this->once())
            ->method('asyncRequest')
            ->willThrowException($exception);
        
        // 设置 logger 的预期行为
        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with(
                '删除二维码规则失败',
                $this->callback(function (array $context) use ($exception) {
                    return isset($context['error']) && $context['error'] === $exception;
                })
            );
        
        // 执行被测试的方法 - 不应抛出异常
        $this->listener->preRemove($qrcodeJump);
    }

    public function testPostLoad(): void
    {
        $qrcodeJump = new QrcodeJump();
        
        // postLoad 方法目前是空的，但仍然需要测试它不会抛出异常
        $this->listener->postLoad($qrcodeJump);
        
        // 简单断言以确认测试已执行
        $this->assertTrue(true);
    }
} 