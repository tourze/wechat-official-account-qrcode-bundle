<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Integration\Repository;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;

class QrcodeTicketRepositoryTest extends TestCase
{
    public function testRepositoryExists(): void
    {
        $this->assertTrue(class_exists(QrcodeTicketRepository::class));
    }
}