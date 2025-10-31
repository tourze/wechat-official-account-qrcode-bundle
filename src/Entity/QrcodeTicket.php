<?php

namespace WechatOfficialAccountQrcodeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;

#[ORM\Entity(repositoryClass: QrcodeTicketRepository::class)]
#[ORM\Table(name: 'wechat_official_account_qrcode_ticket', options: ['comment' => '二维码Ticket'])]
class QrcodeTicket implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '过期时间'])]
    #[Assert\Type(type: '\DateTimeInterface')]
    private ?\DateTimeInterface $expireTime = null;

    #[IndexColumn]
    #[ORM\Column(length: 40, enumType: QrcodeActionName::class, options: ['comment' => '类型'])]
    #[Assert\Choice(callback: [QrcodeActionName::class, 'cases'])]
    private ?QrcodeActionName $actionName = null;

    #[IndexColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '场景ID'])]
    #[Assert\Type(type: 'int')]
    private ?int $sceneId = null;

    #[IndexColumn]
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '场景字符串'])]
    #[Assert\Length(max: 64)]
    private ?string $sceneStr = null;

    #[ORM\Column(length: 128, nullable: true, options: ['comment' => 'TICKET'])]
    #[Assert\Length(max: 128)]
    private ?string $ticket = null;

    #[ORM\Column(length: 255, nullable: true, options: ['comment' => '这个URL是解码后的URL，如果对于二维码有定制需求，可以根据这个来自己生成'])]
    #[Assert\Length(max: 255)]
    #[Assert\Url]
    private ?string $url = null;

    /**
     * @var Collection<int, ScanLog>
     */
    #[Ignore]
    #[ORM\OneToMany(targetEntity: ScanLog::class, mappedBy: 'qrcode', orphanRemoval: true)]
    private Collection $scanLogs;

    public function __construct()
    {
        $this->scanLogs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): void
    {
        $this->account = $account;
    }

    public function getExpireTime(): ?\DateTimeInterface
    {
        return $this->expireTime;
    }

    public function setExpireTime(?\DateTimeInterface $expireTime): void
    {
        $this->expireTime = $expireTime;
    }

    public function getActionName(): ?QrcodeActionName
    {
        return $this->actionName;
    }

    public function setActionName(QrcodeActionName $actionName): void
    {
        $this->actionName = $actionName;
    }

    public function getSceneId(): ?int
    {
        return $this->sceneId;
    }

    public function setSceneId(?int $sceneId): void
    {
        $this->sceneId = $sceneId;
    }

    public function getSceneStr(): ?string
    {
        return $this->sceneStr;
    }

    public function setSceneStr(?string $sceneStr): void
    {
        $this->sceneStr = $sceneStr;
    }

    public function getTicket(): ?string
    {
        return $this->ticket;
    }

    public function setTicket(?string $ticket): void
    {
        $this->ticket = $ticket;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return Collection<int, ScanLog>
     */
    public function getScanLogs(): Collection
    {
        return $this->scanLogs;
    }

    public function addScanLog(ScanLog $scanLog): static
    {
        if (!$this->scanLogs->contains($scanLog)) {
            $this->scanLogs->add($scanLog);
            $scanLog->setQrcode($this);
        }

        return $this;
    }

    public function removeScanLog(ScanLog $scanLog): static
    {
        if ($this->scanLogs->removeElement($scanLog)) {
            // set the owning side to null (unless already changed)
            if ($scanLog->getQrcode() === $this) {
                $scanLog->setQrcode(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf('二维码Ticket #%s', $this->id ?? 'new');
    }
}
