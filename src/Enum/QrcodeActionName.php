<?php

namespace WechatOfficialAccountQrcodeBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum QrcodeActionName: string implements Labelable, Itemable, Selectable, BadgeInterface
{
    use ItemTrait;
    use SelectTrait;

    case QR_SCENE = 'QR_SCENE';
    case QR_STR_SCENE = 'QR_STR_SCENE';
    case QR_LIMIT_SCENE = 'QR_LIMIT_SCENE';
    case QR_LIMIT_STR_SCENE = 'QR_LIMIT_STR_SCENE';

    public function getLabel(): string
    {
        return match ($this) {
            self::QR_SCENE => '临时的整型参数值',
            self::QR_STR_SCENE => '临时的字符串参数值',
            self::QR_LIMIT_SCENE => '永久的整型参数值',
            self::QR_LIMIT_STR_SCENE => '永久的字符串参数值',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::QR_SCENE => BadgeInterface::INFO,
            self::QR_STR_SCENE => BadgeInterface::PRIMARY,
            self::QR_LIMIT_SCENE => BadgeInterface::SUCCESS,
            self::QR_LIMIT_STR_SCENE => BadgeInterface::WARNING,
        };
    }
}
