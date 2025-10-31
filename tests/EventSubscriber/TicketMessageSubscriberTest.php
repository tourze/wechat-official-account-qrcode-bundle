<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\EventSubscriber;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;
use Tourze\PHPUnitSymfonyKernelTest\AbstractEventSubscriberTestCase;
use Tourze\WechatOfficialAccountContracts\UserInterface;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;
use WechatOfficialAccountQrcodeBundle\EventSubscriber\TicketMessageSubscriber;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;
use WechatOfficialAccountServerMessageBundle\Entity\ServerMessage;
use WechatOfficialAccountServerMessageBundle\Event\WechatOfficialAccountServerMessageRequestEvent;

/**
 * @internal
 */
#[CoversClass(TicketMessageSubscriber::class)]
#[RunTestsInSeparateProcesses]
final class TicketMessageSubscriberTest extends AbstractEventSubscriberTestCase
{
    protected function onSetUp(): void
    {
        // Integration test setup if needed
    }

    /** @return array{TicketMessageSubscriber, AsyncInsertService} */
    private function createSubscriberWithMocks(): array
    {
        // 创建 Mock 对象用于验证异步插入行为
        $ticketRepository = self::getService(QrcodeTicketRepository::class);
        $asyncInsertService = $this->createMock(AsyncInsertService::class);

        // @phpstan-ignore-next-line eventSubscriberTest.noDirectInstantiationOfCoveredClass
        $subscriber = new TicketMessageSubscriber($ticketRepository, $asyncInsertService);

        return [$subscriber, $asyncInsertService];
    }

    public function testSaveScanLogWithValidTicket(): void
    {
        [$subscriber, $asyncInsertService] = $this->createSubscriberWithMocks();

        // 创建一个假的 ticket
        $ticket = 'gQH47joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2taZ2Z3TVRtNzJXV1Brb3ZhYmJJAAIEZ23sUwMEmm3sUw==';
        $messageContext = ['Ticket' => $ticket];

        // 创建用于测试的 ServerMessage
        // 使用具体类 ServerMessage 进行 mock 的原因：
        // 1. 这是微信服务器消息的实体类，我们需要模拟其消息上下文数据
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 getContext 方法
        // 3. 通过 mock 具体类可以精确控制消息内容，确保测试的准确性
        $message = $this->createMock(ServerMessage::class);
        $message->method('getContext')->willReturn($messageContext);

        // 创建用于测试的用户对象
        $openId = 'o6_bmjrPTlm6_2sgVt7hMZOPfL2M';
        $user = $this->createMock(UserInterface::class);
        $user->method('getOpenId')->willReturn($openId);

        // 创建事件对象
        // 使用具体类 WechatOfficialAccountServerMessageRequestEvent 进行 mock 的原因：
        // 1. 这是微信服务器消息请求事件类，我们需要模拟其事件数据传递行为
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 getMessage 和 getUser 方法
        // 3. 通过 mock 具体类可以精确控制事件数据，确保测试的准确性
        $event = $this->createMock(WechatOfficialAccountServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getUser')->willReturn($user);

        // 创建测试数据：需要先在数据库中创建 QrcodeTicket
        $qrcodeTicket = new QrcodeTicket();
        $qrcodeTicket->setTicket($ticket);
        $qrcodeTicket->setSceneStr('test-scene');
        $qrcodeTicket->setActionName(QrcodeActionName::QR_STR_SCENE);
        $qrcodeTicket->setExpireTime(new \DateTimeImmutable('+30 days'));

        $entityManager = self::getEntityManager();
        $entityManager->persist($qrcodeTicket);
        $entityManager->flush();

        // 配置Mock期望：asyncInsert应该被调用一次
        $asyncInsertService->expects($this->once())
            ->method('asyncInsert')
            ->with(self::callback(function (ScanLog $scanLog) use ($openId, $qrcodeTicket): bool {
                // 验证传递给asyncInsert的ScanLog对象
                return $scanLog->getOpenId() === $openId
                    && $scanLog->getQrcode() === $qrcodeTicket;
            }))
        ;

        // 执行被测试的方法
        $subscriber->saveScanLog($event);
    }

    public function testSaveScanLogWithEmptyTicket(): void
    {
        [$subscriber, $asyncInsertService] = $this->createSubscriberWithMocks();

        // 创建上下文中没有 Ticket 的消息
        $messageContext = ['MsgType' => 'event'];

        // 创建用于测试的 ServerMessage
        // 使用具体类 ServerMessage 进行 mock 的原因：
        // 1. 这是微信服务器消息的实体类，我们需要模拟其消息上下文数据
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 getContext 方法
        // 3. 通过 mock 具体类可以精确控制消息内容，确保测试的准确性
        $message = $this->createMock(ServerMessage::class);
        $message->method('getContext')->willReturn($messageContext);

        // 创建事件对象
        // 使用具体类 WechatOfficialAccountServerMessageRequestEvent 进行 mock 的原因：
        // 1. 这是微信服务器消息请求事件类，我们需要模拟其事件数据传递行为
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 getMessage 方法
        // 3. 通过 mock 具体类可以精确控制事件数据，确保测试的准确性
        $event = $this->createMock(WechatOfficialAccountServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);

        // ticket 为空时，方法应该正常返回，不抛异常

        // 配置Mock期望：asyncInsert不应该被调用
        $asyncInsertService->expects($this->never())
            ->method('asyncInsert')
        ;

        // 执行被测试的方法
        $subscriber->saveScanLog($event);
    }

    public function testSaveScanLogWithNonExistentTicket(): void
    {
        [$subscriber, $asyncInsertService] = $this->createSubscriberWithMocks();

        // 创建一个不存在的 ticket
        $ticket = 'non_existent_ticket';
        $messageContext = ['Ticket' => $ticket];

        // 创建用于测试的 ServerMessage
        // 使用具体类 ServerMessage 进行 mock 的原因：
        // 1. 这是微信服务器消息的实体类，我们需要模拟其消息上下文数据
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 getContext 方法
        // 3. 通过 mock 具体类可以精确控制消息内容，确保测试的准确性
        $message = $this->createMock(ServerMessage::class);
        $message->method('getContext')->willReturn($messageContext);

        // 创建事件对象
        // 使用具体类 WechatOfficialAccountServerMessageRequestEvent 进行 mock 的原因：
        // 1. 这是微信服务器消息请求事件类，我们需要模拟其事件数据传递行为
        // 2. 该类没有提供对应的接口，而业务逻辑需要依赖具体的 getMessage 方法
        // 3. 通过 mock 具体类可以精确控制事件数据，确保测试的准确性
        $event = $this->createMock(WechatOfficialAccountServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);

        // ticket 不存在时，方法应该正常返回，不抛异常

        // 配置Mock期望：asyncInsert不应该被调用
        $asyncInsertService->expects($this->never())
            ->method('asyncInsert')
        ;

        // 执行被测试的方法
        $subscriber->saveScanLog($event);
    }
}
