<?php

namespace WechatOfficialAccountQrcodeBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class WechatOfficialAccountQrcodeBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            \Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle::class => ['all' => true],
            \WechatOfficialAccountBundle\WechatOfficialAccountBundle::class => ['all' => true],
        ];
    }
}
