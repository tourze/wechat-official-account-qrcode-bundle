<?php

namespace WechatOfficialAccountQrcodeBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;

/**
 * @method QrcodeJump|null find($id, $lockMode = null, $lockVersion = null)
 * @method QrcodeJump|null findOneBy(array $criteria, array $orderBy = null)
 * @method QrcodeJump[]    findAll()
 * @method QrcodeJump[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QrcodeJumpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QrcodeJump::class);
    }
}
