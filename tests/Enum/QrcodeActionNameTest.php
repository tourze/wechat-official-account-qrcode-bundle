<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

/**
 * @internal
 */
#[CoversClass(QrcodeActionName::class)]
final class QrcodeActionNameTest extends AbstractEnumTestCase
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
        $this->assertCount(4, $items);

        $this->assertContains(QrcodeActionName::QR_SCENE, $items);
        $this->assertContains(QrcodeActionName::QR_STR_SCENE, $items);
        $this->assertContains(QrcodeActionName::QR_LIMIT_SCENE, $items);
        $this->assertContains(QrcodeActionName::QR_LIMIT_STR_SCENE, $items);
    }

    public function testToArray(): void
    {
        $enum = QrcodeActionName::QR_SCENE;
        $array = $enum->toArray();
        $this->assertIsArray($array);
        $this->assertCount(2, $array);

        $this->assertArrayHasKey('value', $array);
        $this->assertArrayHasKey('label', $array);

        $this->assertEquals('QR_SCENE', $array['value']);
        $this->assertEquals('临时的整型参数值', $array['label']);

        // 测试其他枚举值
        $strSceneArray = QrcodeActionName::QR_STR_SCENE->toArray();
        $this->assertEquals('QR_STR_SCENE', $strSceneArray['value']);
        $this->assertEquals('临时的字符串参数值', $strSceneArray['label']);
    }
}
