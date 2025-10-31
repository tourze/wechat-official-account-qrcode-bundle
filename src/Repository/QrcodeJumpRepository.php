<?php

namespace WechatOfficialAccountQrcodeBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;

/**
 * @extends ServiceEntityRepository<QrcodeJump>
 */
#[AsRepository(entityClass: QrcodeJump::class)]
class QrcodeJumpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QrcodeJump::class);
    }

    public function save(QrcodeJump $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QrcodeJump $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
