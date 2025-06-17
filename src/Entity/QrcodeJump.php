<?php

namespace WechatOfficialAccountQrcodeBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Action\Editable;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeJumpRepository;

#[AsPermission(title: '二维码规则')]
#[Creatable]
#[Editable]
#[Deletable]
#[ORM\Entity(repositoryClass: QrcodeJumpRepository::class)]
#[ORM\Table(name: 'wechat_official_qrcode_jump', options: ['comment' => '二维码规则'])]
class QrcodeJump
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::STRING, length: 200, unique: true, options: ['comment' => '二维码规则，填服务号的带参二维码url ，必须是http://weixin.qq.com/q/开头的url'])]
    private string $prefix;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '这里填要扫了服务号二维码之后要跳转的小程序的appid'])]
    private string $appid;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::STRING, length: 200, options: ['comment' => '小程序功能页面'])]
    private string $path;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::INTEGER, length: 200, options: ['comment' => '编辑标志位，0 表示新增二维码规则，1 表示修改已有二维码规则'])]
    private int $edit;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::INTEGER, options: ['default' => '0', 'comment' => '0 未发布，1已发布'])]
    private int $state = 0;

    use TimestampableAware;

    public function getId(): ?string
    {
        return $this->id;
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

    public function getEdit(): int
    {
        return $this->edit;
    }

    public function setEdit(int $edit): void
    {
        $this->edit = $edit;
    }

    public function getState(): int
    {
        return $this->state;
    }

    public function setState(int $state): void
    {
        $this->state = $state;
    }
}
