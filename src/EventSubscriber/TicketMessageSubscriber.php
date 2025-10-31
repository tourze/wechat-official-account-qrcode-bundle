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
        if (!is_array($message)) {
            return;
        }

        $ticketValue = ArrayHelper::getValue($message, 'Ticket');
        if (null === $ticketValue || '' === $ticketValue || !is_string($ticketValue)) {
            return;
        }
        $ticket = $this->ticketRepository->findOneBy([
            'ticket' => $ticketValue,
        ]);
        if (null === $ticket) {
            return;
        }

        $user = $event->getUser();
        if (null === $user) {
            return;
        }

        $log = new ScanLog();
        $log->setQrcode($ticket);
        $log->setOpenId($user->getOpenId());
        $log->setUser($event->getUser());
        $this->doctrineService->asyncInsert($log);
    }
}
