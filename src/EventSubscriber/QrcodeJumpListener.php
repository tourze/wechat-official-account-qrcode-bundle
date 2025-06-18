<?php

namespace WechatOfficialAccountQrcodeBundle\EventSubscriber;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;
use Tourze\JsonRPC\Core\Exception\ApiException;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpAddRequest;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpDeleteRequest;
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpPublishRequest;

#[AsEntityListener(event: Events::postLoad, method: 'postLoad', entity: QrcodeJump::class)]
#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: QrcodeJump::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: QrcodeJump::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: QrcodeJump::class)]
class QrcodeJumpListener
{
    public function __construct(
        private readonly OfficialAccountClient $client,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function prePersist(QrcodeJump $obj): void
    {
        // 保存前需要先请求微信接口
        if ('' === $obj->getPrefix()) {
            throw new ApiException('prefix不能为空');
        }
        $request = new QrcodeJumpAddRequest();
        $request->setPrefix($obj->getPrefix());
        $request->setPath($obj->getPath());
        $request->setAppid($obj->getAppid());
        $request->setIsEdit($obj->getEdit());
        try {
            $result = $this->client->request($request);
        } catch (\Exception $exception) {
            throw new ApiException($exception->getMessage(), previous: $exception);
        }

        if (1 === $obj->getState()) { // 如果状态是已发布，创建成功后要调用发布接口
            $publishRequest = new QrcodeJumpPublishRequest();
            $publishRequest->setPrefix($obj->getPrefix());
            try {
                $result2 = $this->client->request($publishRequest);
            } catch (\Exception $exception) {
                throw new ApiException($exception->getMessage(), previous: $exception);
            }
        }
    }

    public function preUpdate(QrcodeJump $obj, PreUpdateEventArgs $eventArgs)
    {
        $oldState = $eventArgs->getOldValue('state');
        if (1 === $obj->getState() && 0 === $oldState) {
            $publishRequest = new QrcodeJumpPublishRequest();
            $publishRequest->setPrefix($obj->getPrefix());
            try {
                $result2 = $this->client->request($publishRequest);
            } catch (\Exception $exception) {
                throw new ApiException($exception->getMessage(), previous: $exception);
            }
        } elseif (1 === $oldState && 0 === $obj->getState()) {
            throw new ApiException('已发布规则不允许修改');
        }
    }

    public function postLoad(QrcodeJump $obj): void
    {
    }

    public function preRemove(QrcodeJump $obj): void
    {
        $request = new QrcodeJumpDeleteRequest();
        $request->setPrefix($obj->getPrefix());
        $request->setAppid($obj->getAppid());
        try {
            $this->client->asyncRequest($request);
        } catch (\Exception $exception) {
            // 远程的有可能不存在了~~~
            $this->logger->error('删除二维码规则失败', [
                'error' => $exception,
            ]);
        }
    }
}
