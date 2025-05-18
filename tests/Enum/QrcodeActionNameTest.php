<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Enum;

use PHPUnit\Framework\TestCase;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

class QrcodeActionNameTest extends TestCase
{
    public function testEnumValues(): void
    {
        $this->assertEquals('QR_SCENE', QrcodeActionName::QR_SCENE->value);
        $this->assertEquals('QR_STR_SCENE', QrcodeActionName::QR_STR_SCENE->value);
        $this->assertEquals('QR_LIMIT_SCENE', QrcodeActionName::QR_LIMIT_SCENE->value);
        $this->assertEquals('QR_LIMIT_STR_SCENE', QrcodeActionName::QR_LIMIT_STR_SCENE->value);
    }

    public function testGetLabel(): void
    {
        $this->assertEquals('临时的整型参数值', QrcodeActionName::QR_SCENE->getLabel());
        $this->assertEquals('临时的字符串参数值', QrcodeActionName::QR_STR_SCENE->getLabel());
        $this->assertEquals('永久的整型参数值', QrcodeActionName::QR_LIMIT_SCENE->getLabel());
        $this->assertEquals('永久的字符串参数值', QrcodeActionName::QR_LIMIT_STR_SCENE->getLabel());
    }

    public function testSelectTrait(): void
    {
        $options = [];
        foreach (QrcodeActionName::cases() as $case) {
            $options[$case->value] = $case->getLabel();
        }
        
        $this->assertIsArray($options);
        $this->assertCount(4, $options);
        
        $this->assertArrayHasKey('QR_SCENE', $options);
        $this->assertEquals('临时的整型参数值', $options['QR_SCENE']);
        
        $this->assertArrayHasKey('QR_STR_SCENE', $options);
        $this->assertEquals('临时的字符串参数值', $options['QR_STR_SCENE']);
        
        $this->assertArrayHasKey('QR_LIMIT_SCENE', $options);
        $this->assertEquals('永久的整型参数值', $options['QR_LIMIT_SCENE']);
        
        $this->assertArrayHasKey('QR_LIMIT_STR_SCENE', $options);
        $this->assertEquals('永久的字符串参数值', $options['QR_LIMIT_STR_SCENE']);
    }

    public function testItemTrait(): void
    {
        $items = QrcodeActionName::cases();
        $this->assertIsArray($items);
        $this->assertCount(4, $items);
        
        $this->assertContains(QrcodeActionName::QR_SCENE, $items);
        $this->assertContains(QrcodeActionName::QR_STR_SCENE, $items);
        $this->assertContains(QrcodeActionName::QR_LIMIT_SCENE, $items);
        $this->assertContains(QrcodeActionName::QR_LIMIT_STR_SCENE, $items);
    }
} 