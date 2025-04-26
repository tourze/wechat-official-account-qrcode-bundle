<?php

namespace WechatOfficialAccountQrcodeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DoctrineEnhanceBundle\Traits\TimestampableAware;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\EasyAdmin\Attribute\Action\Creatable;
use Tourze\EasyAdmin\Attribute\Action\Deletable;
use Tourze\EasyAdmin\Attribute\Action\Editable;
use Tourze\EasyAdmin\Attribute\Column\BoolColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Field\FormField;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;
use WechatOfficialAccountQrcodeBundle\Repository\QrcodeTicketRepository;

#[AsPermission(title: '二维码Ticket')]
#[Creatable]
#[Editable]
#[Deletable]
#[ORM\Entity(repositoryClass: QrcodeTicketRepository::class)]
#[ORM\Table(name: 'wechat_official_account_qrcode_ticket', options: ['comment' => '二维码Ticket'])]
class QrcodeTicket
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

    use TimestampableAware;

    #[BoolColumn]
    #[IndexColumn]
    #[TrackColumn]
    #[Groups(['admin_curd', 'restful_read', 'restful_read', 'restful_write'])]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[ListColumn(order: 97)]
    #[FormField(order: 97)]
    private ?bool $valid = false;

    #[ListColumn]
    #[FormField]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Account $account = null;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['comment' => '过期时间'])]
    private ?\DateTimeInterface $expireTime = null;

    /**
     * 临时二维码默认有效期30天
     */
    #[ListColumn]
    #[FormField]
    #[IndexColumn]
    #[ORM\Column(length: 40, enumType: QrcodeActionName::class, options: ['comment' => '类型'])]
    private ?QrcodeActionName $actionName = null;

    #[ListColumn]
    #[FormField]
    #[IndexColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '场景ID'])]
    private ?int $sceneId = null;

    #[ListColumn]
    #[FormField]
    #[IndexColumn]
    #[ORM\Column(length: 64, nullable: true, options: ['comment' => '场景字符串'])]
    private ?string $sceneStr = null;

    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 128, nullable: true, options: ['comment' => 'TICKET'])]
    private ?string $ticket = null;

    /**
     * @var string|null 这个URL是解码后的URL，如果对于二维码有定制需求，可以根据这个来自己生成
     */
    #[ListColumn]
    #[FormField]
    #[ORM\Column(length: 255, nullable: true, options: ['comment' => 'URL'])]
    private ?string $url = null;

    #[Ignore]
    #[ORM\OneToMany(mappedBy: 'qrcode', targetEntity: ScanLog::class, orphanRemoval: true)]
    private Collection $scanLogs;

    public function __construct()
    {
        $this->scanLogs = new ArrayCollection();
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getExpireTime(): ?\DateTimeInterface
    {
        return $this->expireTime;
    }

    public function setExpireTime(\DateTimeInterface $expireTime): static
    {
        $this->expireTime = $expireTime;

        return $this;
    }

    public function getActionName(): ?QrcodeActionName
    {
        return $this->actionName;
    }

    public function setActionName(QrcodeActionName $actionName): static
    {
        $this->actionName = $actionName;

        return $this;
    }

    public function getSceneId(): ?int
    {
        return $this->sceneId;
    }

    public function setSceneId(?int $sceneId): static
    {
        $this->sceneId = $sceneId;

        return $this;
    }

    public function getSceneStr(): ?string
    {
        return $this->sceneStr;
    }

    public function setSceneStr(?string $sceneStr): static
    {
        $this->sceneStr = $sceneStr;

        return $this;
    }

    public function getTicket(): ?string
    {
        return $this->ticket;
    }

    public function setTicket(?string $ticket): static
    {
        $this->ticket = $ticket;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
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
}
