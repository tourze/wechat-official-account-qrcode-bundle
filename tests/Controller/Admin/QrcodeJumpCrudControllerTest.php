<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatOfficialAccountQrcodeBundle\Controller\Admin\QrcodeJumpCrudController;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;

/**
 * @internal
 */
#[CoversClass(QrcodeJumpCrudController::class)]
#[RunTestsInSeparateProcesses]
class QrcodeJumpCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): QrcodeJumpCrudController
    {
        return new QrcodeJumpCrudController();
    }

    private function getController(): QrcodeJumpCrudController
    {
        return $this->getControllerService();
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(QrcodeJump::class, QrcodeJumpCrudController::getEntityFqcn());
    }

    public function testConfigureCrud(): void
    {
        $crud = $this->getController()->configureCrud(Crud::new());

        $this->assertInstanceOf(Crud::class, $crud);
    }

    public function testConfigureActions(): void
    {
        $actions = $this->getController()->configureActions(Actions::new());

        $this->assertInstanceOf(Actions::class, $actions);
    }

    public function testConfigureFields(): void
    {
        $fields = iterator_to_array($this->getController()->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields);

        // 验证关键字段存在
        $fieldTypes = array_map(fn ($field) => is_object($field) ? get_class($field) : $field, $fields);

        $this->assertContains(IdField::class, $fieldTypes);
        $this->assertContains(UrlField::class, $fieldTypes); // prefix field
        $this->assertContains(TextField::class, $fieldTypes); // appid and path fields
        $this->assertContains(ChoiceField::class, $fieldTypes); // edit and state fields
        $this->assertContains(DateTimeField::class, $fieldTypes); // createTime and updateTime fields
    }

    public function testConfigureFilters(): void
    {
        $filters = $this->getController()->configureFilters(Filters::new());

        $this->assertInstanceOf(Filters::class, $filters);
    }

    public function testFieldsConfiguration(): void
    {
        $fields = iterator_to_array($this->getController()->configureFields(Crud::PAGE_INDEX));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field) && method_exists($field, 'getAsDto')) {
                $fieldNames[] = $field->getAsDto()->getProperty();
            }
        }

        $expectedFields = ['id', 'prefix', 'appid', 'path', 'edit', 'state', 'createTime', 'updateTime'];

        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be configured");
        }
    }

    public function testFiltersConfiguration(): void
    {
        $filters = $this->getController()->configureFilters(Filters::new());

        $this->assertInstanceOf(Filters::class, $filters);

        // 简化验证，只检查类型
        $this->assertInstanceOf(Filters::class, $filters);
    }

    public function testCrudConfiguration(): void
    {
        $crud = $this->getController()->configureCrud(Crud::new());

        // 验证实体标签配置
        $this->assertNotNull($crud->getAsDto()->getEntityLabelInSingular());
        $this->assertNotNull($crud->getAsDto()->getEntityLabelInPlural());
        $this->assertSame('二维码规则', $crud->getAsDto()->getEntityLabelInSingular());
        $this->assertSame('二维码规则', $crud->getAsDto()->getEntityLabelInPlural());
    }

    public function testActionsConfiguration(): void
    {
        $actions = $this->getController()->configureActions(Actions::new());

        $this->assertInstanceOf(Actions::class, $actions);

        // 验证操作配置
        $indexActions = $actions->getAsDto(Crud::PAGE_INDEX)->getActions();
        $this->assertNotEmpty($indexActions);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '二维码URL前缀' => ['二维码URL前缀'];
        yield '小程序AppID' => ['小程序AppID'];
        yield '小程序页面路径' => ['小程序页面路径'];
        yield '编辑标志' => ['编辑标志'];
        yield '发布状态' => ['发布状态'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'prefix' => ['prefix'];
        yield 'appid' => ['appid'];
        yield 'path' => ['path'];
        yield 'edit' => ['edit'];
        yield 'state' => ['state'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'prefix' => ['prefix'];
        yield 'appid' => ['appid'];
        yield 'path' => ['path'];
        yield 'edit' => ['edit'];
        yield 'state' => ['state'];
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', $this->generateAdminUrl(Action::NEW));
        $this->assertResponseIsSuccessful();

        // 提交空表单验证必填字段错误
        $form = $crawler->selectButton('Create')->form();
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);

        // 验证必填字段错误信息
        $errorText = $crawler->filter('.invalid-feedback')->text();
        $this->assertStringContainsString('This value should not be blank', $errorText);
    }
}
