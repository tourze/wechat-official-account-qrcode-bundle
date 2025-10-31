<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatOfficialAccountQrcodeBundle\WechatOfficialAccountQrcodeBundle;

/**
 * @internal
 */
#[CoversClass(WechatOfficialAccountQrcodeBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatOfficialAccountQrcodeBundleTest extends AbstractBundleTestCase
{
}
