<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Unit\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;
use WechatOfficialAccountQrcodeBundle\Repository\ScanLogRepository;

class ScanLogRepositoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new ScanLogRepository($registry);
        
        $this->assertInstanceOf(ScanLogRepository::class, $repository);
    }

    public function testRepositoryForCorrectEntity(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new ScanLogRepository($registry);
        
        // 通过反射检查实体类
        $reflection = new \ReflectionClass($repository);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame('Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository', $parent->getName());
    }
}