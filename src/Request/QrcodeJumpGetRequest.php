<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountQrcodeBundle\Exception\QrcodeJumpRequestException;

/**
 * 删除已设置的二维码规则.
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/qrcode/qrcodejumpget.html
 */
class QrcodeJumpGetRequest extends WithAccountRequest
{
    private ?string $appid = null;

    private ?int $type = null;

    /**
     * @var array<string>|null
     */
    private ?array $prefixList = null;

    private ?int $pageNum = null;

    private ?int $pageSize = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/wxopen/qrcodejumpget';
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestOptions(): array
    {
        $this->validateRequiredFields();

        $json = [
            'get_type' => $this->getType(),
            'appid' => $this->getAppid(),
        ];

        $json = array_merge($json, $this->getTypeSpecificFields());

        return [
            'json' => $json,
        ];
    }

    private function validateRequiredFields(): void
    {
        if (null === $this->getType()) {
            throw new QrcodeJumpRequestException('缺少type入参');
        }

        if (null === $this->getAppid()) {
            throw new QrcodeJumpRequestException('缺少appid入参');
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function getTypeSpecificFields(): array
    {
        if (1 === $this->getType()) {
            return $this->getPrefixListFields();
        }

        if (2 === $this->getType()) {
            return $this->getPaginationFields();
        }

        throw new QrcodeJumpRequestException('入参Type异常');
    }

    /**
     * @return array<string, mixed>
     */
    private function getPrefixListFields(): array
    {
        if (null === $this->getPrefixList()) {
            throw new QrcodeJumpRequestException('缺少prefixList入参');
        }

        return ['prefixList' => $this->getPrefixList()];
    }

    /**
     * @return array<string, mixed>
     */
    private function getPaginationFields(): array
    {
        if (null === $this->getPageNum()) {
            throw new QrcodeJumpRequestException('缺少pageNum入参');
        }
        if (null === $this->getPageSize()) {
            throw new QrcodeJumpRequestException('缺少pageSize入参');
        }

        return [
            'pageNum' => $this->getPageNum(),
            'pageSize' => $this->getPageSize(),
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

    /**
     * @return array<string>|null
     */
    public function getPrefixList(): ?array
    {
        return $this->prefixList;
    }

    /**
     * @param array<string>|null $prefixList
     */
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
