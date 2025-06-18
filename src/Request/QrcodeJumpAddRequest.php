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
    private ?string $prefix = null;

    private ?string $appid = null;

    private ?string $path = null;

    private ?int $isEdit = null;

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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getIsEdit(): ?int
    {
        return $this->isEdit;
    }

    public function setIsEdit(int $isEdit): void
    {
        $this->isEdit = $isEdit;
    }
}
