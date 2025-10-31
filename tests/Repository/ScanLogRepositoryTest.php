<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;
use WechatOfficialAccountQrcodeBundle\Repository\ScanLogRepository;

/**
 * @internal
 */
#[CoversClass(ScanLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class ScanLogRepositoryTest extends AbstractRepositoryTestCase
{
    private ScanLogRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ScanLogRepository::class);
    }

    protected function createNewEntity(): object
    {
        $qrcodeTicket = new QrcodeTicket();
        // Account is optional, so we don't set it in tests
        $qrcodeTicket->setActionName(QrcodeActionName::QR_SCENE);
        $qrcodeTicket->setSceneId(123);
        $qrcodeTicket->setTicket('test-ticket-' . uniqid());
        $qrcodeTicket->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket-' . uniqid());
        $qrcodeTicket->setExpireTime(new \DateTimeImmutable('+1 day')); // 必需字段

        // 需要先持久化 QrcodeTicket，因为 ScanLog 需要引用它
        self::getEntityManager()->persist($qrcodeTicket);
        self::getEntityManager()->flush();

        $entity = new ScanLog();
        $entity->setQrcode($qrcodeTicket);
        $entity->setOpenId('test-openid-' . uniqid());

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<ScanLog>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testSave(): void
    {
        $qrcodeTicket = new QrcodeTicket();
        // Account is optional, so we don't set it in tests
        $qrcodeTicket->setActionName(QrcodeActionName::QR_SCENE);
        $qrcodeTicket->setSceneId(123);
        $qrcodeTicket->setTicket('test-ticket');
        $qrcodeTicket->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $qrcodeTicket->setExpireTime(new \DateTimeImmutable('+1 day'));
        self::getEntityManager()->persist($qrcodeTicket);
        self::getEntityManager()->flush();

        $entity = new ScanLog();
        $entity->setQrcode($qrcodeTicket);
        $entity->setOpenId('test-openid');

        $this->repository->save($entity);

        $this->assertNotNull($entity->getId());
        $this->assertEquals('test-openid', $entity->getOpenId());
    }

    public function testRemove(): void
    {
        $qrcodeTicket = new QrcodeTicket();
        // Account is optional, so we don't set it in tests
        $qrcodeTicket->setActionName(QrcodeActionName::QR_SCENE);
        $qrcodeTicket->setSceneId(123);
        $qrcodeTicket->setTicket('test-ticket');
        $qrcodeTicket->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $qrcodeTicket->setExpireTime(new \DateTimeImmutable('+1 day'));
        self::getEntityManager()->persist($qrcodeTicket);
        self::getEntityManager()->flush();

        $entity = new ScanLog();
        $entity->setQrcode($qrcodeTicket);
        $entity->setOpenId('test-openid');

        $this->repository->save($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $foundEntity = $this->repository->find($id);
        $this->assertNull($foundEntity);
    }

    public function testFindAllWithMultipleRecordsShouldReturnArrayOfEntities(): void
    {
        // 清理所有现有数据，确保测试独立性
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }

        $qrcodeTicket = new QrcodeTicket();
        // Account is optional, so we don't set it in tests
        $qrcodeTicket->setActionName(QrcodeActionName::QR_SCENE);
        $qrcodeTicket->setSceneId(123);
        $qrcodeTicket->setTicket('test-ticket');
        $qrcodeTicket->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $qrcodeTicket->setExpireTime(new \DateTimeImmutable('+1 day'));
        self::getEntityManager()->persist($qrcodeTicket);
        self::getEntityManager()->flush();

        $entity1 = new ScanLog();
        $entity1->setQrcode($qrcodeTicket);
        $entity1->setOpenId('openid1');
        $this->repository->save($entity1);

        $entity2 = new ScanLog();
        $entity2->setQrcode($qrcodeTicket);
        $entity2->setOpenId('openid2');
        $this->repository->save($entity2);

        $results = $this->repository->findAll();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertContainsOnlyInstancesOf(ScanLog::class, $results);
    }

    public function testFindByIdShouldReturnCorrectEntity(): void
    {
        $qrcodeTicket = new QrcodeTicket();
        // Account is optional, so we don't set it in tests
        $qrcodeTicket->setActionName(QrcodeActionName::QR_SCENE);
        $qrcodeTicket->setSceneId(123);
        $qrcodeTicket->setTicket('test-ticket');
        $qrcodeTicket->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $qrcodeTicket->setExpireTime(new \DateTimeImmutable('+1 day'));
        self::getEntityManager()->persist($qrcodeTicket);
        self::getEntityManager()->flush();

        $entity = new ScanLog();
        $entity->setQrcode($qrcodeTicket);
        $entity->setOpenId('test-openid');
        $this->repository->save($entity);

        $id = $entity->getId();
        $foundEntity = $this->repository->find($id);

        $this->assertNotNull($foundEntity);
        $this->assertEquals($id, $foundEntity->getId());
        $this->assertEquals('test-openid', $foundEntity->getOpenId());
    }
}
