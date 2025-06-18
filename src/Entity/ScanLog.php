<?php

namespace WechatOfficialAccountQrcodeBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\WechatOfficialAccountContracts\UserInterface;
use WechatOfficialAccountQrcodeBundle\Repository\ScanLogRepository;

#[ORM\Entity(repositoryClass: ScanLogRepository::class, readOnly: true)]
#[ORM\Table(name: 'ims_wechat_qrcode_scan_log', options: ['comment' => 'Ticket扫描记录'])]
class ScanLog implements \Stringable
{
    use CreateTimeAware;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    #[ORM\ManyToOne(inversedBy: 'scanLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QrcodeTicket $qrcode = null;

    #[ORM\Column(length: 64, options: ['comment' => '扫描OpenID'])]
    private ?string $openId = null;

    #[ORM\ManyToOne]
    private ?UserInterface $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQrcode(): ?QrcodeTicket
    {
        return $this->qrcode;
    }

    public function setQrcode(?QrcodeTicket $qrcode): static
    {
        $this->qrcode = $qrcode;

        return $this;
    }

    public function getOpenId(): ?string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): static
    {
        $this->openId = $openId;

        return $this;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('扫描记录 #%s', $this->id ?? 'new');
    }
}
