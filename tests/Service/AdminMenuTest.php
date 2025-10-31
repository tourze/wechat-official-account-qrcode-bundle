<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatOfficialAccountQrcodeBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    protected function onSetUp(): void
    {
        // 从容器中获取AdminMenu服务，而非直接实例化
        $this->adminMenu = static::getService(AdminMenu::class);
    }

    public function testAdminMenuExists(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);
    }

    public function testGetMenuItems(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        $this->assertIsArray($menuItems);
        $this->assertNotEmpty($menuItems);

        // 验证菜单项不为空且是数组结构
        foreach ($menuItems as $item) {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('label', $item);
            $this->assertArrayHasKey('icon', $item);
        }
    }

    public function testMenuItemsCount(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        // 验证菜单结构：应该有一个主菜单组
        $this->assertCount(1, $menuItems);

        // 验证子菜单有3个CRUD菜单项
        $this->assertArrayHasKey('submenu', $menuItems[0]);
        $this->assertCount(3, $menuItems[0]['submenu']);
    }

    public function testReturnTypeAnnotation(): void
    {
        $reflection = new \ReflectionMethod($this->adminMenu, 'getMenuItems');
        $docComment = $reflection->getDocComment();

        $this->assertStringContainsString('@return array<', false !== $docComment ? $docComment : '');
    }

    public function testGetMenuItemsReturnStaticArray(): void
    {
        $menuItems1 = $this->adminMenu->getMenuItems();
        $menuItems2 = $this->adminMenu->getMenuItems();

        // 两次调用应该返回相同的结构
        $this->assertCount(count($menuItems1), $menuItems2);
        $this->assertEquals($menuItems1, $menuItems2);
    }

    public function testBasicStructure(): void
    {
        $menuItems = $this->adminMenu->getMenuItems();

        // 基本验证：确保有1个主菜单组
        $this->assertCount(1, $menuItems);

        // 验证主菜单组结构
        $mainMenu = $menuItems[0];
        $this->assertIsArray($mainMenu);
        $this->assertArrayHasKey('label', $mainMenu);
        $this->assertArrayHasKey('icon', $mainMenu);
        $this->assertArrayHasKey('submenu', $mainMenu);
        $this->assertEquals('微信公众号二维码', $mainMenu['label']);

        // 验证子菜单有3个CRUD菜单项
        $submenu = $mainMenu['submenu'];
        $this->assertCount(3, $submenu);

        foreach ($submenu as $item) {
            $this->assertIsArray($item);
            $this->assertArrayHasKey('label', $item);
            $this->assertArrayHasKey('icon', $item);
            $this->assertArrayHasKey('controller', $item);
            $this->assertArrayHasKey('permission', $item);
        }
    }
}
