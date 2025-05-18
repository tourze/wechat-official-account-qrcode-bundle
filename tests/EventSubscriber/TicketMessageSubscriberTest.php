<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineAsyncBundle\Service\DoctrineService;
use Tourze\WechatOfficialAccountContracts\UserInterface;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;
use WechatOfficialAccountQrcodeBundle\EventSubscriber\TicketMessageSubscriber;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;
use WechatOfficialAccountServerMessageBundle\Entity\ServerMessage;
use WechatOfficialAccountServerMessageBundle\Event\WechatOfficialAccountServerMessageRequestEvent;

class TicketMessageSubscriberTest extends TestCase
{
    private QrcodeTicketRepository $ticketRepository;
    private DoctrineService $doctrineService;
    private TicketMessageSubscriber $subscriber;

    protected function setUp(): void
    {
        $this->ticketRepository = $this->createMock(QrcodeTicketRepository::class);
        $this->doctrineService = $this->createMock(DoctrineService::class);
        $this->subscriber = new TicketMessageSubscriber(
            $this->ticketRepository,
            $this->doctrineService
        );
    }

    public function testSaveScanLogWithValidTicket(): void
    {
        // 创建一个假的 ticket
        $ticket = 'gQH47joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2taZ2Z3TVRtNzJXV1Brb3ZhYmJJAAIEZ23sUwMEmm3sUw==';
        $messageContext = ['Ticket' => $ticket];
        
        // 创建用于测试的 ServerMessage
        $message = $this->createMock(ServerMessage::class);
        $message->method('getContext')->willReturn($messageContext);
        
        // 创建用于测试的用户对象
        $openId = 'o6_bmjrPTlm6_2sgVt7hMZOPfL2M';
        $user = $this->createMock(UserInterface::class);
        $user->method('getOpenId')->willReturn($openId);
        
        // 创建事件对象
        $event = $this->createMock(WechatOfficialAccountServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        $event->method('getUser')->willReturn($user);
        
        // 设置 ticketRepository 的预期行为
        $qrcodeTicket = new QrcodeTicket();
        $this->ticketRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['ticket' => $ticket])
            ->willReturn($qrcodeTicket);
        
        // 设置 doctrineService 的预期行为
        $this->doctrineService
            ->expects($this->once())
            ->method('asyncInsert')
            ->with($this->callback(function (ScanLog $log) use ($qrcodeTicket, $openId, $user) {
                return $log->getQrcode() === $qrcodeTicket
                    && $log->getOpenId() === $openId
                    && $log->getUser() === $user;
            }));
        
        // 执行被测试的方法
        $this->subscriber->saveScanLog($event);
    }

    public function testSaveScanLogWithEmptyTicket(): void
    {
        // 创建上下文中没有 Ticket 的消息
        $messageContext = ['MsgType' => 'event'];
        
        // 创建用于测试的 ServerMessage
        $message = $this->createMock(ServerMessage::class);
        $message->method('getContext')->willReturn($messageContext);
        
        // 创建事件对象
        $event = $this->createMock(WechatOfficialAccountServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        
        // ticket 为空时，不应调用 findOneBy 和 asyncInsert
        $this->ticketRepository->expects($this->never())->method('findOneBy');
        $this->doctrineService->expects($this->never())->method('asyncInsert');
        
        // 执行被测试的方法
        $this->subscriber->saveScanLog($event);
    }

    public function testSaveScanLogWithNonExistentTicket(): void
    {
        // 创建一个不存在的 ticket
        $ticket = 'non_existent_ticket';
        $messageContext = ['Ticket' => $ticket];
        
        // 创建用于测试的 ServerMessage
        $message = $this->createMock(ServerMessage::class);
        $message->method('getContext')->willReturn($messageContext);
        
        // 创建事件对象
        $event = $this->createMock(WechatOfficialAccountServerMessageRequestEvent::class);
        $event->method('getMessage')->willReturn($message);
        
        // ticket 存在但未找到记录时，findOneBy 会被调用但返回 null，asyncInsert 不应被调用
        $this->ticketRepository
            ->expects($this->once())
            ->method('findOneBy')
            ->with(['ticket' => $ticket])
            ->willReturn(null);
        
        $this->doctrineService->expects($this->never())->method('asyncInsert');
        
        // 执行被测试的方法
        $this->subscriber->saveScanLog($event);
    }
} 