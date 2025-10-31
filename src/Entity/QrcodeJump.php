<?php

namespace WechatOfficialAccountQrcodeBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeJumpRepository;

#[ORM\Entity(repositoryClass: QrcodeJumpRepository::class)]
#[ORM\Table(name: 'wechat_official_qrcode_jump', options: ['comment' => '二维码规则'])]
class QrcodeJump implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;

    #[ORM\Column(type: Types::STRING, length: 200, unique: true, options: ['comment' => '二维码规则，填服务号的带参二维码url ，必须是http://weixin.qq.com/q/开头的url'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    #[Assert\Url]
    private string $prefix;

    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '这里填要扫了服务号二维码之后要跳转的小程序的appid'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private string $appid;

    #[ORM\Column(type: Types::STRING, length: 200, options: ['comment' => '小程序功能页面'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 200)]
    private string $path;

    #[ORM\Column(type: Types::INTEGER, length: 200, options: ['comment' => '编辑标志位，0 表示新增二维码规则，1 表示修改已有二维码规则'])]
    #[Assert\Choice(choices: [0, 1])]
    private int $edit;

    #[ORM\Column(type: Types::INTEGER, options: ['default' => '0', 'comment' => '0 未发布，1已发布'])]
    #[Assert\Choice(choices: [0, 1])]
    private int $state = 0;

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

    public function __toString(): string
    {
        return sprintf('二维码规则 #%s', $this->id ?? 'new');
    }
}
