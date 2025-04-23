<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 增加或修改二维码规则
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/qrcode/qrcodejumpadd.html
 */
class QrcodeJumpAddRequest extends WithAccountRequest
{
    /**
     * @var string 二维码规则，填服务号的带参二维码url ，必须是http://weixin.qq.com/q/开头的url，例如http://weixin.qq.com/q/02P5KzM_xxxxx
     */
    private string $prefix;

    /**
     * @var string 这里填要扫了服务号二维码之后要跳转的小程序的appid
     */
    private string $appid;

    /**
     * @var string 小程序功能页面
     */
    private string $path;

    /**
     * @var int 编辑标志位，0 表示新增二维码规则，1 表示修改已有二维码规则（注意，已经发布的规则，不支持修改）
     */
    private int $isEdit;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpadd';
    }

    public function getRequestOptions(): ?array
    {
        $json = [
            'prefix' => $this->getPrefix(),
            'appid' => $this->getAppid(),
            'path' => $this->getPath(),
            'is_edit' => $this->getIsEdit(),
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

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getIsEdit(): int
    {
        return $this->isEdit;
    }

    public function setIsEdit(int $isEdit): void
    {
        $this->isEdit = $isEdit;
    }
}
