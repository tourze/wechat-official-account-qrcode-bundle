<?php

namespace WechatOfficialAccountQrcodeBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;

/**
 * @method QrcodeTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method QrcodeTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method QrcodeTicket[]    findAll()
 * @method QrcodeTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QrcodeTicketRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QrcodeTicket::class);
    }
}
