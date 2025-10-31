<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 删除已设置的二维码规则.
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/qrcode/qrcodejumpdelete.html
 */
class QrcodeJumpDeleteRequest extends WithAccountRequest
{
    private ?string $prefix = null;

    private ?string $appid = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdelete';
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestOptions(): array
    {
        $json = [
            'prefix' => $this->getPrefix(),
            'appid' => $this->getAppid(),
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

    public function getAppid(): ?string
    {
        return $this->appid;
    }

    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }
}
