<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Unit\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeJumpRepository;

class QrcodeJumpRepositoryTest extends TestCase
{
    public function testConstructor(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new QrcodeJumpRepository($registry);
        
        $this->assertInstanceOf(QrcodeJumpRepository::class, $repository);
    }

    public function testRepositoryForCorrectEntity(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new QrcodeJumpRepository($registry);
        
        // 通过反射检查实体类
        $reflection = new \ReflectionClass($repository);
        $parent = $reflection->getParentClass();
        $this->assertNotFalse($parent);
        $this->assertSame('Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository', $parent->getName());
    }
}