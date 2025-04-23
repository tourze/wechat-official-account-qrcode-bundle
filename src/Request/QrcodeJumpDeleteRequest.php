<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 删除已设置的二维码规则
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/qrcode/qrcodejumpdelete.html
 */
class QrcodeJumpDeleteRequest extends WithAccountRequest
{
    /**
     * @var string 二维码规则，填服务号的带参二维码url ，必须是http://weixin.qq.com/q/开头的url，例如http://weixin.qq.com/q/02P5KzM_xxxxx
     */
    private string $prefix;

    /**
     * @var string 这里填要扫了服务号二维码之后要跳转的小程序的appid
     */
    private string $appid;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpdelete';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'prefix' => $this->getPrefix(),
            'appid' => $this->getAppid(),
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

    public function getAppid(): string
    {
        return $this->appid;
    }

    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }
}
