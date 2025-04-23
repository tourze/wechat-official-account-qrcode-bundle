<?php

namespace WechatOfficialAccountQrcodeBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;

/**
 * @method ScanLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScanLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScanLog[]    findAll()
 * @method ScanLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScanLogRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScanLog::class);
    }
}
