<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;

/**
 * @internal
 */
#[CoversClass(QrcodeTicketRepository::class)]
#[RunTestsInSeparateProcesses]
final class QrcodeTicketRepositoryTest extends AbstractRepositoryTestCase
{
    private QrcodeTicketRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(QrcodeTicketRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new QrcodeTicket();
        $entity->setActionName(QrcodeActionName::QR_SCENE);
        $entity->setSceneId(123);
        $entity->setTicket('test-ticket-' . uniqid());
        $entity->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket-' . uniqid());
        $entity->setExpireTime(new \DateTimeImmutable('+1 day')); // 必需字段

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<QrcodeTicket>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testSave(): void
    {
        $entity = new QrcodeTicket();
        $entity->setActionName(QrcodeActionName::QR_SCENE);
        $entity->setSceneId(123);
        $entity->setTicket('test-ticket');
        $entity->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $entity->setExpireTime(new \DateTimeImmutable('+1 day'));

        $this->repository->save($entity);

        $this->assertNotNull($entity->getId());
        $this->assertEquals(QrcodeActionName::QR_SCENE, $entity->getActionName());
    }

    public function testRemove(): void
    {
        $entity = new QrcodeTicket();
        $entity->setActionName(QrcodeActionName::QR_SCENE);
        $entity->setSceneId(123);
        $entity->setTicket('test-ticket');
        $entity->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $entity->setExpireTime(new \DateTimeImmutable('+1 day'));

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

        $entity1 = new QrcodeTicket();
        $entity1->setActionName(QrcodeActionName::QR_SCENE);
        $entity1->setSceneId(123);
        $entity1->setTicket('ticket1');
        $entity1->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=ticket1');
        $entity1->setExpireTime(new \DateTimeImmutable('+1 day'));
        $this->repository->save($entity1);

        $entity2 = new QrcodeTicket();
        $entity2->setActionName(QrcodeActionName::QR_STR_SCENE);
        $entity2->setSceneStr('scene2');
        $entity2->setTicket('ticket2');
        $entity2->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=ticket2');
        $entity2->setExpireTime(new \DateTimeImmutable('+2 days'));
        $this->repository->save($entity2);

        $results = $this->repository->findAll();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertContainsOnlyInstancesOf(QrcodeTicket::class, $results);
    }

    public function testFindByIdShouldReturnCorrectEntity(): void
    {
        $entity = new QrcodeTicket();
        $entity->setActionName(QrcodeActionName::QR_SCENE);
        $entity->setSceneId(123);
        $entity->setTicket('test-ticket');
        $entity->setUrl('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=test-ticket');
        $entity->setExpireTime(new \DateTimeImmutable('+1 day'));
        $this->repository->save($entity);

        $id = $entity->getId();
        $foundEntity = $this->repository->find($id);

        $this->assertNotNull($foundEntity);
        $this->assertEquals($id, $foundEntity->getId());
        $this->assertEquals(QrcodeActionName::QR_SCENE, $foundEntity->getActionName());
    }
}
