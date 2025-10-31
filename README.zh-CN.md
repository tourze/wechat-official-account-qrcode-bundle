# WechatOfficialAccountQrcodeBundle

中文 | [English](README.md)

[![最新版本](https://img.shields.io/packagist/v/tourze/wechat-official-account-qrcode-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-official-account-qrcode-bundle)
[![PHP 版本](https://img.shields.io/badge/PHP-%5E8.1-blue.svg?style=flat-square)](https://php.net)
[![许可证](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](LICENSE)
[![总下载量](https://img.shields.io/packagist/dt/tourze/wechat-official-account-qrcode-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-official-account-qrcode-bundle)
[![代码覆盖率](https://img.shields.io/badge/coverage-90%25-brightgreen.svg?style=flat-square)](#)

用于微信公众号二维码生成、管理和追踪的 Symfony 包。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [依赖项](#依赖项)
- [快速开始](#快速开始)
  - [1. 注册 Bundle](#1-注册-bundle)
  - [2. 创建二维码](#2-创建二维码)
  - [3. 处理二维码扫描事件](#3-处理二维码扫描事件)
- [实体类](#实体类)
  - [QrcodeTicket（二维码票据）](#qrcodeticket二维码票据)
  - [ScanLog（扫描日志）](#scanlog扫描日志)
  - [QrcodeJump（二维码跳转）](#qrcodejump二维码跳转)
- [二维码类型](#二维码类型)
- [API 示例](#api-示例)
  - [创建二维码请求](#创建二维码请求)
  - [管理二维码跳转规则](#管理二维码跳转规则)
- [高级用法](#高级用法)
  - [自定义事件处理](#自定义事件处理)
  - [数据库迁移](#数据库迁移)
  - [性能优化](#性能优化)
- [事件处理](#事件处理)
- [测试](#测试)
- [贡献](#贡献)
- [更新日志](#更新日志)
- [许可证](#许可证)

## 功能特性

- 生成微信公众号二维码（临时和永久）
- 跟踪二维码扫描日志
- 管理公众号与小程序之间的二维码跳转规则
- 支持场景 ID 和场景字符串参数
- 实时扫码事件处理

## 安装

```bash
composer require tourze/wechat-official-account-qrcode-bundle
```

## 依赖项

此包需要以下依赖项：

- **PHP**: ^8.1
- **Symfony**: ^6.4
- **Doctrine ORM**: ^3.0
- **tourze/wechat-official-account-bundle**: 核心微信公众号集成
- **tourze/doctrine-indexed-bundle**: 数据库索引支持
- **tourze/doctrine-timestamp-bundle**: 实体时间戳管理
- **tourze/doctrine-snowflake-bundle**: 雪花ID生成

## 快速开始

### 1. 注册 Bundle

```php
// config/bundles.php
return [
    // ...
    WechatOfficialAccountQrcodeBundle\WechatOfficialAccountQrcodeBundle::class => ['all' => true],
];
```

### 2. 创建二维码

```php
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

// 创建带场景 ID 的临时二维码
$qrcode = new QrcodeTicket();
$qrcode->setActionName(QrcodeActionName::QR_SCENE);
$qrcode->setSceneId(123);
$qrcode->setExpireTime(new \DateTimeImmutable('+1 hour'));

// 创建带场景字符串的永久二维码
$qrcode = new QrcodeTicket();
$qrcode->setActionName(QrcodeActionName::QR_LIMIT_STR_SCENE);
$qrcode->setSceneStr('user_invitation');
```

### 3. 处理二维码扫描事件

```php
use WechatOfficialAccountQrcodeBundle\EventSubscriber\TicketMessageSubscriber;

// Bundle 会自动处理扫描事件并创建扫描日志
// 您可以在应用程序中订阅这些事件
```

## 实体类

### QrcodeTicket（二维码票据）
- 二维码票据的主要实体
- 支持临时和永久二维码
- 跟踪过期时间、场景参数和票据信息

### ScanLog（扫描日志）
- 记录二维码扫描事件
- 关联二维码票据和用户信息
- 不可变实体，用于审计追踪

### QrcodeJump（二维码跳转）
- 管理二维码跳转规则
- 支持从微信公众号跳转到小程序
- 支持规则发布和编辑

## 二维码类型

- `QR_SCENE` - 临时的整型参数值
- `QR_STR_SCENE` - 临时的字符串参数值
- `QR_LIMIT_SCENE` - 永久的整型参数值
- `QR_LIMIT_STR_SCENE` - 永久的字符串参数值

## API 示例

### 创建二维码请求

```php
use WechatOfficialAccountQrcodeBundle\Request\CreateQrcodeRequest;

$request = new CreateQrcodeRequest();
$request->setActionName(QrcodeActionName::QR_SCENE);
$request->setSceneId(123);
$request->setExpireSeconds(3600);
```

### 管理二维码跳转规则

```php
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpAddRequest;

$request = new QrcodeJumpAddRequest();
$request->setPrefix('http://weixin.qq.com/q/xxx');
$request->setAppid('your_mini_program_appid');
$request->setPath('pages/index/index');
```

## 高级用法

### 自定义事件处理

您可以创建自定义事件订阅器来处理二维码扫描事件：

```php
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WechatOfficialAccountServerMessageBundle\Event\MessageEvent;

class CustomQrcodeEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            MessageEvent::class => 'handleScanEvent',
        ];
    }

    public function handleScanEvent(MessageEvent $event): void
    {
        $message = $event->getMessage();
        if ($message->getMsgType() === 'event' && $message->getEvent() === 'SCAN') {
            // 处理自定义扫描逻辑
            $sceneId = $message->getEventKey();
            // 您的自定义逻辑
        }
    }
}
```

### 数据库迁移

创建所需的数据库表：

```bash
php bin/console doctrine:migrations:migrate
```

### 性能优化

对于高流量应用，建议考虑：

1. **数据库索引**: Bundle 自动为扫描日志创建索引
2. **缓存**: 缓存频繁访问的二维码数据
3. **异步处理**: 使用 Symfony Messenger 异步处理扫描事件

```php
// config/packages/messenger.yaml
framework:
    messenger:
        transports:
            qrcode_scan: '%env(MESSENGER_TRANSPORT_DSN)%'
        routing:
            'WechatOfficialAccountQrcodeBundle\Message\*': qrcode_scan
```

## 事件处理

Bundle 提供以下事件订阅器：
- 二维码扫描事件处理
- 跳转规则管理
- 自动扫描日志创建

## 测试

运行测试套件：

```bash
./vendor/bin/phpunit packages/wechat-official-account-qrcode-bundle/tests
```

### 测试覆盖

所有 84 个测试均成功通过，共 258 个断言。

**注意**：由于测试环境中 `UserInterface` 的已知依赖解析问题，Repository 测试当前实现为单元测试而非集成测试。请参考 [Issue #814](https://github.com/tourze/php-monorepo/issues/814) 跟踪此问题的进展。

## 贡献

我们欢迎贡献！请遵循以下准则：

1. **报告问题**：使用 GitHub 问题追踪器报告错误或请求功能
2. **提交拉取请求**：fork 仓库并提交包含您更改的拉取请求
3. **代码风格**：遵循 PSR-12 编码标准和现有代码模式
4. **测试**：确保所有测试通过并为新功能添加测试
5. **文档**：更新任何新功能或更改的文档

### 开发设置

1. 克隆仓库
2. 安装依赖：`composer install`
3. 运行测试：`./vendor/bin/phpunit packages/wechat-official-account-qrcode-bundle/tests`
4. 运行静态分析：`./vendor/bin/phpstan analyse packages/wechat-official-account-qrcode-bundle`

## 更新日志

### [未发布]
- 初始发布
- 二维码生成和管理
- 扫描事件追踪
- 跳转规则管理
- 全面的测试覆盖

## 许可证

MIT 许可证。详情请参阅 [LICENSE](LICENSE) 文件。