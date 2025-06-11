<?php

namespace WechatOfficialAccountQrcodeBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService as DoctrineService;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;
use WechatOfficialAccountServerMessageBundle\Event\WechatOfficialAccountServerMessageRequestEvent;
use Yiisoft\Arrays\ArrayHelper;

class TicketMessageSubscriber
{
    public function __construct(
        private readonly QrcodeTicketRepository $ticketRepository,
        private readonly DoctrineService $doctrineService,
    ) {
    }

    #[AsEventListener]
    public function saveScanLog(WechatOfficialAccountServerMessageRequestEvent $event): void
    {
        $message = $event->getMessage()->getContext();

        $ticket = ArrayHelper::getValue($message, 'Ticket');
        if (empty($ticket)) {
            return;
        }
        $ticket = $this->ticketRepository->findOneBy([
            'ticket' => $ticket,
        ]);
        if (!$ticket) {
            return;
        }

        $log = new ScanLog();
        $log->setQrcode($ticket);
        $log->setOpenId($event->getUser()->getOpenId());
        $log->setUser($event->getUser());
        $this->doctrineService->asyncInsert($log);
    }
}
