<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Integration\Repository;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Repository\ScanLogRepository;

class ScanLogRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(ScanLogRepository::class));
    }
}