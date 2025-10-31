<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatOfficialAccountQrcodeBundle\Exception\QrcodeJumpRequestException;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpRequestException::class)]
final class QrcodeJumpRequestExceptionTest extends AbstractExceptionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testExceptionMessage(): void
    {
        $message = '测试异常消息';
        $exception = new QrcodeJumpRequestException($message);
        $this->assertSame($message, $exception->getMessage());
    }

    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new QrcodeJumpRequestException('测试');
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }
}
