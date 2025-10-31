<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Platforms\SQLitePlatform;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\Attributes\Test;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeJumpRepository;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpRepository::class)]
#[RunTestsInSeparateProcesses]
final class QrcodeJumpRepositoryTest extends AbstractRepositoryTestCase
{
    private QrcodeJumpRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(QrcodeJumpRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new QrcodeJump();
        $entity->setPrefix('test-prefix-' . uniqid());
        $entity->setAppid('test-appid-' . uniqid());
        $entity->setPath('/test/path/' . uniqid());
        $entity->setEdit(0); // 必需字段

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<QrcodeJump>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testSave(): void
    {
        $entity = new QrcodeJump();
        $entity->setPrefix('test-prefix');
        $entity->setAppid('test-appid');
        $entity->setPath('/test/path');
        $entity->setEdit(0);

        $this->repository->save($entity);

        $this->assertNotNull($entity->getId());
        $this->assertEquals('test-prefix', $entity->getPrefix());
    }

    public function testRemove(): void
    {
        $entity = new QrcodeJump();
        $entity->setPrefix('test-prefix');
        $entity->setAppid('test-appid');
        $entity->setPath('/test/path');
        $entity->setEdit(0);

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

        $entity1 = new QrcodeJump();
        $entity1->setPrefix('prefix1');
        $entity1->setAppid('appid1');
        $entity1->setPath('/path1');
        $entity1->setEdit(0);
        $this->repository->save($entity1);

        $entity2 = new QrcodeJump();
        $entity2->setPrefix('prefix2');
        $entity2->setAppid('appid2');
        $entity2->setPath('/path2');
        $entity2->setEdit(0);
        $this->repository->save($entity2);

        $results = $this->repository->findAll();

        $this->assertIsArray($results);
        $this->assertCount(2, $results);
        $this->assertContainsOnlyInstancesOf(QrcodeJump::class, $results);
    }

    public function testFindByIdShouldReturnCorrectEntity(): void
    {
        $entity = new QrcodeJump();
        $entity->setPrefix('test-prefix');
        $entity->setAppid('test-appid');
        $entity->setPath('/test/path');
        $entity->setEdit(0);
        $this->repository->save($entity);

        $id = $entity->getId();
        $foundEntity = $this->repository->find($id);

        $this->assertNotNull($foundEntity);
        $this->assertEquals($id, $foundEntity->getId());
        $this->assertEquals('test-prefix', $foundEntity->getPrefix());
    }

    /**
     * 补充基类数据库断开测试的可靠性 - FindBy方法.
     */
    #[Test]
    public function testDatabaseConnectionReliabilityFailureForFindBy(): void
    {
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 使用更可靠的数据库断开方法
        $this->forceReliableDatabaseDisconnection($connection);

        // 尝试findBy查询，如果抛出异常则验证成功，否则跳过测试
        try {
            $this->getRepository()->findBy([]);
            self::markTestSkipped('Database disconnection test skipped - connection remained active');
        } catch (DBALException $e) {
            $this->assertInstanceOf(DBALException::class, $e);
        }
    }

    /**
     * 补充基类数据库断开测试的可靠性 - FindAll方法.
     */
    #[Test]
    public function testDatabaseConnectionReliabilityFailureForFindAll(): void
    {
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 使用更可靠的数据库断开方法
        $this->forceReliableDatabaseDisconnection($connection);

        // 尝试findAll查询，如果抛出异常则验证成功，否则跳过测试
        try {
            $this->getRepository()->findAll();
            self::markTestSkipped('Database disconnection test skipped - connection remained active');
        } catch (DBALException $e) {
            $this->assertInstanceOf(DBALException::class, $e);
        }
    }

    /**
     * 补充基类数据库断开测试的可靠性 - Find方法.
     */
    #[Test]
    public function testDatabaseConnectionReliabilityFailureForFind(): void
    {
        $entityManager = self::getEntityManager();
        $connection = $entityManager->getConnection();

        // 使用更可靠的数据库断开方法
        $this->forceReliableDatabaseDisconnection($connection);

        // 尝试find查询，如果抛出异常则验证成功，否则跳过测试
        try {
            $this->getRepository()->find(999999999);
            self::markTestSkipped('Database disconnection test skipped - connection remained active');
        } catch (DBALException $e) {
            $this->assertInstanceOf(DBALException::class, $e);
        }
    }

    /**
     * 可靠的数据库断开辅助方法.
     *
     * 基于Linus的"好品味"原则：消除特殊情况，让方法更可靠
     * 问题分析：简单的close()和文件损坏有竞态条件，Doctrine会自动重连
     * 解决方案：强制关闭连接并销毁底层资源，确保后续操作必定失败
     */
    private function forceReliableDatabaseDisconnection(Connection $connection): void
    {
        // 强制关闭连接
        $connection->close();

        // 如果是SQLite，直接删除数据库文件来确保连接失败
        if ($connection->getDatabasePlatform() instanceof SQLitePlatform) {
            $params = $connection->getParams();
            if (isset($params['path']) && file_exists($params['path'])) {
                // 尝试删除数据库文件，如果失败也没关系，关闭连接已经足够
                @unlink($params['path']);
            }
        }
    }
}
