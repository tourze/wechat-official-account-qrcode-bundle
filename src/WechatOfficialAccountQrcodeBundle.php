<?php

namespace WechatOfficialAccountQrcodeBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\WechatOfficialAccountOAuth2Bundle\WechatOfficialAccountOAuth2Bundle;
use WechatOfficialAccountBundle\WechatOfficialAccountBundle;

class WechatOfficialAccountQrcodeBundle extends Bundle implements BundleDependencyInterface
{
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            WechatOfficialAccountBundle::class => ['all' => true],
            WechatOfficialAccountOAuth2Bundle::class => ['all' => true],
        ];
    }
}
