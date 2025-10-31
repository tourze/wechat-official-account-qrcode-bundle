# WechatOfficialAccountQrcodeBundle

[中文](README.zh-CN.md) | English

[![Latest Version](https://img.shields.io/packagist/v/tourze/wechat-official-account-qrcode-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-official-account-qrcode-bundle)
[![PHP Version](https://img.shields.io/badge/PHP-%5E8.1-blue.svg?style=flat-square)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/wechat-official-account-qrcode-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/wechat-official-account-qrcode-bundle)
[![Code Coverage](https://img.shields.io/badge/coverage-90%25-brightgreen.svg?style=flat-square)](#)

A Symfony bundle for WeChat Official Account QR code generation, management, and tracking.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Dependencies](#dependencies)
- [Quick Start](#quick-start)
  - [1. Register the Bundle](#1-register-the-bundle)
  - [2. Create QR Code](#2-create-qr-code)
  - [3. Handle QR Code Scan Events](#3-handle-qr-code-scan-events)
- [Configuration](#configuration)
- [Entities](#entities)
  - [QrcodeTicket](#qrcodeticket)
  - [ScanLog](#scanlog)
  - [QrcodeJump](#qrcodejump)
- [QR Code Types](#qr-code-types)
- [API Examples](#api-examples)
  - [Create QR Code Request](#create-qr-code-request)
  - [Manage QR Code Jump Rules](#manage-qr-code-jump-rules)
- [Advanced Usage](#advanced-usage)
  - [Custom Event Handling](#custom-event-handling)
  - [Database Migrations](#database-migrations)
  - [Performance Optimization](#performance-optimization)
- [Events](#events)
- [Testing](#testing)
- [License](#license)

## Features

- Generate WeChat Official Account QR codes (temporary and permanent)
- Track QR code scan logs
- Manage QR code jump rules between WeChat Official Account and Mini Program
- Support for both scene ID and scene string parameters
- Real-time scan event handling

## Installation

```bash
composer require tourze/wechat-official-account-qrcode-bundle
```

## Dependencies

This bundle requires the following dependencies:

- **PHP**: ^8.1
- **Symfony**: ^6.4
- **Doctrine ORM**: ^3.0
- **tourze/wechat-official-account-bundle**: Core WeChat Official Account integration
- **tourze/doctrine-indexed-bundle**: Database indexing support
- **tourze/doctrine-timestamp-bundle**: Entity timestamp management
- **tourze/doctrine-snowflake-bundle**: Snowflake ID generation

## Quick Start

### 1. Register the Bundle

```php
// config/bundles.php
return [
    // ...
    WechatOfficialAccountQrcodeBundle\WechatOfficialAccountQrcodeBundle::class => ['all' => true],
];
```

### 2. Create QR Code

```php
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Enum\QrcodeActionName;

// Create a temporary QR code with scene ID
$qrcode = new QrcodeTicket();
$qrcode->setActionName(QrcodeActionName::QR_SCENE);
$qrcode->setSceneId(123);
$qrcode->setExpireTime(new \DateTimeImmutable('+1 hour'));

// Create a permanent QR code with scene string
$qrcode = new QrcodeTicket();
$qrcode->setActionName(QrcodeActionName::QR_LIMIT_STR_SCENE);
$qrcode->setSceneStr('user_invitation');
```

### 3. Handle QR Code Scan Events

```php
use WechatOfficialAccountQrcodeBundle\EventSubscriber\TicketMessageSubscriber;

// The bundle automatically handles scan events and creates scan logs
// You can subscribe to these events in your application
```

## Configuration

The bundle requires the following dependencies:
- `tourze/doctrine-indexed-bundle` - For database indexing
- `tourze/wechat-official-account-bundle` - For WeChat Official Account integration

## Entities

### QrcodeTicket
- Main entity for QR code tickets
- Supports temporary and permanent QR codes
- Tracks expiration time, scene parameters, and ticket information

### ScanLog
- Records QR code scan events
- Links to the QR code ticket and user information
- Immutable entity for audit trail

### QrcodeJump
- Manages QR code jump rules
- Enables jumping from WeChat Official Account to Mini Program
- Supports rule publishing and editing

## QR Code Types

- `QR_SCENE` - Temporary QR code with integer scene ID
- `QR_STR_SCENE` - Temporary QR code with string scene parameter
- `QR_LIMIT_SCENE` - Permanent QR code with integer scene ID
- `QR_LIMIT_STR_SCENE` - Permanent QR code with string scene parameter

## API Examples

### Create QR Code Request

```php
use WechatOfficialAccountQrcodeBundle\Request\CreateQrcodeRequest;

$request = new CreateQrcodeRequest();
$request->setActionName(QrcodeActionName::QR_SCENE);
$request->setSceneId(123);
$request->setExpireSeconds(3600);
```

### Manage QR Code Jump Rules

```php
use WechatOfficialAccountQrcodeBundle\Request\QrcodeJumpAddRequest;

$request = new QrcodeJumpAddRequest();
$request->setPrefix('http://weixin.qq.com/q/xxx');
$request->setAppid('your_mini_program_appid');
$request->setPath('pages/index/index');
```

## Advanced Usage

### Custom Event Handling

You can create custom event subscribers to handle QR code scan events:

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
            // Handle custom scan logic
            $sceneId = $message->getEventKey();
            // Your custom logic here
        }
    }
}
```

### Database Migrations

Create the required database tables:

```bash
php bin/console doctrine:migrations:migrate
```

### Performance Optimization

For high-traffic applications, consider:

1. **Database Indexing**: The bundle automatically creates indexes for scan logs
2. **Caching**: Cache frequently accessed QR code data
3. **Async Processing**: Handle scan events asynchronously using Symfony Messenger

```php
// config/packages/messenger.yaml
framework:
    messenger:
        transports:
            qrcode_scan: '%env(MESSENGER_TRANSPORT_DSN)%'
        routing:
            'WechatOfficialAccountQrcodeBundle\Message\*': qrcode_scan
```

## Events

The bundle provides event subscribers for:
- QR code scan event handling
- Jump rule management
- Automatic scan log creation

## Testing

Run the test suite:

```bash
./vendor/bin/phpunit packages/wechat-official-account-qrcode-bundle/tests
```

### Test Coverage

All 84 tests pass successfully with 258 assertions. 

**Note**: Repository tests are currently implemented as unit tests instead of integration tests due to a known dependency resolution issue with `UserInterface` in the test environment. 
See [Issue #814](https://github.com/tourze/php-monorepo/issues/814) for tracking progress on this issue.

## Contributing

We welcome contributions! Please follow these guidelines:

1. **Report Issues**: Use the GitHub issue tracker to report bugs or request features
2. **Submit Pull Requests**: Fork the repository and submit pull requests with your changes
3. **Code Style**: Follow PSR-12 coding standards and existing code patterns
4. **Testing**: Ensure all tests pass and add tests for new functionality
5. **Documentation**: Update documentation for any new features or changes

### Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Run tests: `./vendor/bin/phpunit packages/wechat-official-account-qrcode-bundle/tests`
4. Run static analysis: `./vendor/bin/phpstan analyse packages/wechat-official-account-qrcode-bundle`

## Changelog

### [Unreleased]
- Initial release
- QR code generation and management
- Scan event tracking
- Jump rule management
- Comprehensive test coverage

## License

MIT License. See [LICENSE](LICENSE) for details.