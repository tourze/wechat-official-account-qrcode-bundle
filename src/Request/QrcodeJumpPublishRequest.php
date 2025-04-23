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
    /**
     * @var string 二维码规则，填服务号的带参二维码url ，必须是http://weixin.qq.com/q/开头的url，例如http://weixin.qq.com/q/02P5KzM_xxxxx
     */
    private string $prefix;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpadd';
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

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }
}
