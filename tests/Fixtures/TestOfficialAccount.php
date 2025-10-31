<?php

namespace WechatOfficialAccountQrcodeBundle\Tests\Fixtures;

use Doctrine\ORM\Mapping as ORM;
use Tourze\WechatOfficialAccountContracts\OfficialAccountInterface;

#[ORM\Entity]
#[ORM\Table(name: 'test_official_account')]
class TestOfficialAccount implements OfficialAccountInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 64, nullable: true)]
    private ?string $appId = null;

    public function __construct(?string $appId = null)
    {
        $this->appId = $appId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppId(): ?string
    {
        return $this->appId;
    }

    public function setAppId(?string $appId): self
    {
        $this->appId = $appId;

        return $this;
    }
}
