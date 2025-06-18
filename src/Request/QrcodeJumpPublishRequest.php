<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 发布已设置的二维码规则
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/qrcode/qrcodejumppublish.html
 */
class QrcodeJumpPublishRequest extends WithAccountRequest
{
    private ?string $prefix = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumppublish';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'prefix' => $this->getPrefix(),
        ];

        return [
            'json' => $json,
        ];
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
