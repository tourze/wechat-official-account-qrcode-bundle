<?php

namespace WechatOfficialAccountQrcodeBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;

/**
 * @extends ServiceEntityRepository<QrcodeTicket>
 */
#[AsRepository(entityClass: QrcodeTicket::class)]
class QrcodeTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QrcodeTicket::class);
    }

    public function save(QrcodeTicket $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(QrcodeTicket $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
