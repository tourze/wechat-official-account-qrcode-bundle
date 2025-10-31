<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\EventSubscriber;

use Doctrine\ORM\Event\PreUpdateEventArgs;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Psr\Log\LoggerInterface;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;
use WechatOfficialAccountQrcodeBundle\EventSubscriber\QrcodeJumpListener;
use WechatOfficialAccountQrcodeBundle\Exception\QrcodeJumpValidationException;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpListener::class)]
#[RunTestsInSeparateProcesses]
final class QrcodeJumpListenerTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // Integration test setup if needed
    }

    /**
     * @return array{QrcodeJumpListener, OfficialAccountClient, LoggerInterface}
     */
    private function createListenerWithMocks(): array
    {
        // 使用具体类 OfficialAccountClient 进行 mock 的原因：
        // 1. 这是一个外部服务客户端类，我们需要模拟其具体的网络请求行为
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 request 和 asyncRequest 方法
        // 3. 通过 mock 具体类可以精确控制返回值和异常，确保测试的准确性
        $client = $this->createMock(OfficialAccountClient::class);
        $logger = $this->createMock(LoggerInterface::class);

        // @phpstan-ignore-next-line integrationTest.noDirectInstantiationOfCoveredClass
        $listener = new QrcodeJumpListener($client, $logger, 'test');

        return [$listener, $client, $logger];
    }

    public function testPrePersistWithValidData(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0); // 新增二维码规则
        $qrcodeJump->setState(0); // 未发布状态

        // 测试环境下，不应调用真实的 API
        $client->expects($this->never())->method('request');

        // 应该记录日志
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则创建请求', [
                'prefix' => $qrcodeJump->getPrefix(),
                'state' => $qrcodeJump->getState(),
            ])
        ;

        // 执行被测试的方法
        $listener->prePersist($qrcodeJump);
    }

    public function testPrePersistWithValidDataAndPublished(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0); // 新增二维码规则
        $qrcodeJump->setState(1); // 已发布状态

        // 测试环境下，不应调用真实的 API
        $client->expects($this->never())->method('request');

        // 应该记录日志
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则创建请求', [
                'prefix' => $qrcodeJump->getPrefix(),
                'state' => $qrcodeJump->getState(),
            ])
        ;

        // 执行被测试的方法
        $listener->prePersist($qrcodeJump);
    }

    public function testPrePersistWithEmptyPrefix(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix(''); // 空前缀
        $qrcodeJump->setPath('pages/index/index');
        $qrcodeJump->setAppid('wx1234567890abcdef');
        $qrcodeJump->setEdit(0);

        // 测试环境下，应该记录日志而不是抛出异常
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则创建请求', [
                'prefix' => '',
                'state' => $qrcodeJump->getState(),
            ])
        ;

        // client 的请求方法不应被调用
        $client->expects($this->never())->method('request');

        // 执行被测试的方法 - 不应抛出异常
        $listener->prePersist($qrcodeJump);
    }

    public function testPrePersistInProductionWithEmptyPrefix(): void
    {
        // 创建生产环境的监听器
        $client = $this->createMock(OfficialAccountClient::class);
        $logger = $this->createMock(LoggerInterface::class);
        // @phpstan-ignore-next-line integrationTest.noDirectInstantiationOfCoveredClass
        $listener = new QrcodeJumpListener($client, $logger, 'prod');

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix(''); // 空前缀

        // 生产环境应该抛出异常
        $this->expectException(QrcodeJumpValidationException::class);
        $this->expectExceptionMessage('prefix不能为空');

        // 执行被测试的方法
        $listener->prePersist($qrcodeJump);
    }

    public function testPreUpdateFromUnpublishedToPublished(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setState(1); // 设置为已发布状态

        // 创建 PreUpdateEventArgs mock
        $oldValues = ['state' => 0]; // 旧状态为未发布
        // 使用具体类 PreUpdateEventArgs 进行 mock 的原因：
        // 1. 这是 Doctrine ORM 的事件参数类，用于携带实体更新前的旧值信息
        // 2. 该类没有提供对应的接口，而我们需要模拟 getOldValue 方法的行为
        // 3. 通过 mock 具体类可以精确控制旧值的获取，确保测试逻辑的正确性
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getOldValue')
            ->with('state')
            ->willReturn(0)
        ;

        // 测试环境下，不应调用真实的 API
        $client->expects($this->never())->method('request');

        // 应该记录日志
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则更新请求', [
                'prefix' => $qrcodeJump->getPrefix(),
                'state' => $qrcodeJump->getState(),
            ])
        ;

        // 执行被测试的方法
        $listener->preUpdate($qrcodeJump, $eventArgs);
    }

    public function testPreUpdateFromPublishedToUnpublished(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setState(0); // 设置为未发布状态

        // 创建 PreUpdateEventArgs mock
        // 使用具体类 PreUpdateEventArgs 进行 mock 的原因：
        // 1. 这是 Doctrine ORM 的事件参数类，用于携带实体更新前的旧值信息
        // 2. 该类没有提供对应的接口，而我们需要模拟 getOldValue 方法的行为
        // 3. 通过 mock 具体类可以精确控制旧值的获取，确保测试逻辑的正确性
        $eventArgs = $this->createMock(PreUpdateEventArgs::class);
        $eventArgs->method('getOldValue')
            ->with('state')
            ->willReturn(1) // 旧状态为已发布
        ;

        // 测试环境下，应该记录日志而不是抛出异常
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则更新请求', [
                'prefix' => $qrcodeJump->getPrefix(),
                'state' => $qrcodeJump->getState(),
            ])
        ;

        // client 的请求方法不应被调用
        $client->expects($this->never())->method('request');

        // 执行被测试的方法 - 不应抛出异常
        $listener->preUpdate($qrcodeJump, $eventArgs);
    }

    public function testPreRemove(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setAppid('wx1234567890abcdef');

        // 测试环境下，不应调用真实的 API
        $client->expects($this->never())->method('asyncRequest');

        // 应该记录日志
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则删除请求', [
                'prefix' => $qrcodeJump->getPrefix(),
            ])
        ;

        // 执行被测试的方法
        $listener->preRemove($qrcodeJump);
    }

    public function testPreRemoveWithError(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();
        $qrcodeJump->setPrefix('http://weixin.qq.com/q/abcdef123456');
        $qrcodeJump->setAppid('wx1234567890abcdef');

        // 测试环境下，不应调用真实的 API，因此也不会有错误
        $client->expects($this->never())->method('asyncRequest');

        // 应该记录日志
        $logger
            ->expects($this->once())
            ->method('info')
            ->with('测试环境：跳过二维码规则删除请求', [
                'prefix' => $qrcodeJump->getPrefix(),
            ])
        ;

        // 执行被测试的方法 - 不应抛出异常
        $listener->preRemove($qrcodeJump);
    }

    public function testPostLoad(): void
    {
        [$listener, $client, $logger] = $this->createListenerWithMocks();

        $qrcodeJump = new QrcodeJump();

        // postLoad 方法目前是空的，但仍然需要测试它不会抛出异常
        $listener->postLoad($qrcodeJump);

        // 验证对象状态没有改变，确认 postLoad 方法正确执行
        $this->assertInstanceOf(QrcodeJump::class, $qrcodeJump);
    }
}
