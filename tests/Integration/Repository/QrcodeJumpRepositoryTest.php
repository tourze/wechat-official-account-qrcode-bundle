<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Integration\Repository;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeJumpRepository;

class QrcodeJumpRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(QrcodeJumpRepository::class));
    }
}