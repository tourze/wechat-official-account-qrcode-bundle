<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use Tourze\JsonRPC\Core\Exception\ApiException;
use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 删除已设置的二维码规则
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/qrcode/qrcodejumpget.html
 */
class QrcodeJumpGetRequest extends WithAccountRequest
{
    private ?string $appid = null;

    private ?int $type = null;

    private ?array $prefixList = null;

    private ?int $pageNum = null;

    private ?int $pageSize = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpget';
    }

    public function getRequestOptions(): ?array
    {
        if ($this->getType() === null) {
            throw new ApiException('缺少type入参');
        }
        
        if ($this->getAppid() === null) {
            throw new ApiException('缺少appid入参');
        }
        
        $json = [
            'get_type' => $this->getType(),
            'appid' => $this->getAppid(),
        ];
        
        if (1 === $this->getType()) {
            if (null === $this->getPrefixList()) {
                throw new ApiException('缺少prefixList入参');
            }
            $json['prefixList'] = $this->getPrefixList();
        } elseif (2 === $this->getType()) {
            if (null === $this->getPageNum()) {
                throw new ApiException('缺少pageNum入参');
            }
            if (null === $this->getPageSize()) {
                throw new ApiException('缺少pageSize入参');
            }
            $json['pageNum'] = $this->getPageNum();
            $json['pageSize'] = $this->getPageSize();
        } else {
            throw new ApiException('入参Type异常');
        }

        return [
            'json' => $json,
        ];
    }

    public function getAppid(): ?string
    {
        return $this->appid;
    }

    public function setAppid(string $appid): void
    {
        $this->appid = $appid;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getPrefixList(): ?array
    {
        return $this->prefixList;
    }

    public function setPrefixList(?array $prefixList): void
    {
        $this->prefixList = $prefixList;
    }

    public function getPageNum(): ?int
    {
        return $this->pageNum;
    }

    public function setPageNum(?int $pageNum): void
    {
        $this->pageNum = $pageNum;
    }

    public function getPageSize(): ?int
    {
        return $this->pageSize;
    }

    public function setPageSize(?int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }
}
