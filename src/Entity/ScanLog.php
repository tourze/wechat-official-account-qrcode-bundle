<?php

namespace WechatOfficialAccountQrcodeBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use WechatOfficialAccountBundle\Entity\User;
use WechatOfficialAccountQrcodeBundle\Repository\ScanLogRepository;

#[ORM\Entity(repositoryClass: ScanLogRepository::class, readOnly: true)]
#[ORM\Table(name: 'ims_wechat_qrcode_scan_log', options: ['comment' => 'Ticket扫描记录'])]
class ScanLog
{
    #[ListColumn(order: -1)]
    #[ExportColumn]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[ORM\ManyToOne(inversedBy: 'scanLogs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QrcodeTicket $qrcode = null;

    #[ORM\Column(length: 64, options: ['comment' => '扫描OpenID'])]
    private ?string $openId = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    public function setCreateTime(?\DateTimeInterface $createdAt): self
    {
        $this->createTime = $createdAt;

        return $this;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
