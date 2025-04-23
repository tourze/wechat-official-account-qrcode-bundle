<?php

namespace WechatOfficialAccountQrcodeBundle\Request;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

/**
 * 生成带参数的二维码
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Account_Management/Generating_a_Parametric_QR_Code.html
 */
class CreateQrcodeRequest extends WithAccountRequest
{
    /**
     * @var int 该二维码有效时间，以秒为单位
     */
    private int $expireSeconds = 60;

    /**
     * @var QrcodeActionName 二维码类型
     */
    private QrcodeActionName $actionName = QrcodeActionName::QR_STR_SCENE;

    /**
     * @var int|null 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
     */
    private ?int $sceneId = null;

    /**
     * @var string|null 场景值ID（字符串形式的ID），字符串类型，长度限制为1到64
     */
    private ?string $sceneStr = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/cgi-bin/qrcode/create';
    }

    public function getRequestOptions(): ?array
    {
        $scene = [];
        if (in_array($this->getActionName(), [QrcodeActionName::QR_STR_SCENE, QrcodeActionName::QR_LIMIT_STR_SCENE])) {
            $scene['scene_str'] = $this->getSceneStr();
        }
        if (in_array($this->getActionName(), [QrcodeActionName::QR_SCENE, QrcodeActionName::QR_LIMIT_SCENE])) {
            $scene['scene_id'] = $this->getSceneId();
        }

        return [
            'json' => [
                'expire_seconds' => $this->getExpireSeconds(),
                'action_name' => $this->getActionName(),
                'action_info' => [
                    'scene' => $scene,
                ],
            ],
        ];
    }

    public function getExpireSeconds(): int
    {
        return $this->expireSeconds;
    }

    public function setExpireSeconds(int $expireSeconds): void
    {
        $this->expireSeconds = $expireSeconds;
    }

    public function getActionName(): QrcodeActionName
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
}
