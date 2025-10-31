<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatOfficialAccountQrcodeBundle\Controller\Admin\QrcodeTicketCrudController;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;

/**
 * @internal
 */
#[CoversClass(QrcodeTicketCrudController::class)]
#[RunTestsInSeparateProcesses]
class QrcodeTicketCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): QrcodeTicketCrudController
    {
        return new QrcodeTicketCrudController();
    }

    private function getController(): QrcodeTicketCrudController
    {
        return $this->getControllerService();
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '有效状态' => ['有效状态'];
        yield '公众号账户' => ['公众号账户'];
        yield '过期时间' => ['过期时间'];
        yield '二维码类型' => ['二维码类型'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'valid' => ['valid'];
        yield 'account' => ['account'];
        yield 'expireTime' => ['expireTime'];
        yield 'actionName' => ['actionName'];
        yield 'sceneStr' => ['sceneStr'];
        yield 'url' => ['url'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'valid' => ['valid'];
        yield 'account' => ['account'];
        yield 'expireTime' => ['expireTime'];
        yield 'actionName' => ['actionName'];
        yield 'sceneStr' => ['sceneStr'];
        yield 'url' => ['url'];
    }

    public function testGetEntityFqcn(): void
    {
        $this->assertSame(QrcodeTicket::class, QrcodeTicketCrudController::getEntityFqcn());
    }

    public function testConfigureCrud(): void
    {
        $controller = $this->getController();
        $crud = $controller->configureCrud(Crud::new());

        $this->assertInstanceOf(Crud::class, $crud);
        $this->assertSame('二维码Ticket', $crud->getAsDto()->getEntityLabelInSingular());
        $this->assertSame('二维码Ticket', $crud->getAsDto()->getEntityLabelInPlural());
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
        $this->assertContains(BooleanField::class, $fieldTypes); // valid field
        $this->assertContains(AssociationField::class, $fieldTypes); // account field
        $this->assertContains(DateTimeField::class, $fieldTypes); // expireTime, createTime, updateTime
        $this->assertContains(ChoiceField::class, $fieldTypes); // actionName field
        $this->assertContains(IntegerField::class, $fieldTypes); // sceneId field
        $this->assertContains(TextField::class, $fieldTypes); // sceneStr, ticket fields
        $this->assertContains(UrlField::class, $fieldTypes); // url field
    }

    public function testConfigureFilters(): void
    {
        $filters = $this->getController()->configureFilters(Filters::new());

        $this->assertInstanceOf(Filters::class, $filters);

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

        $expectedFields = [
            'id',
            'valid',
            'account',
            'expireTime',
            'actionName',
            'sceneId',
            'sceneStr',
            'ticket',
            'url',
            'createTime',
            'updateTime',
        ];

        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be configured");
        }
    }

    public function testCrudLabels(): void
    {
        $crud = $this->getController()->configureCrud(Crud::new());

        $this->assertSame('二维码Ticket', $crud->getAsDto()->getEntityLabelInSingular());
        $this->assertSame('二维码Ticket', $crud->getAsDto()->getEntityLabelInPlural());
    }

    public function testSearchFields(): void
    {
        $crud = $this->getController()->configureCrud(Crud::new());

        $searchFields = $crud->getAsDto()->getSearchFields() ?? [];
        $expectedSearchFields = ['id', 'ticket', 'sceneId', 'sceneStr'];

        foreach ($expectedSearchFields as $field) {
            $this->assertContains($field, $searchFields, "Search field '{$field}' should be configured");
        }
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();
        $crawler = $client->request('GET', $this->generateAdminUrl(Action::NEW));
        $this->assertResponseIsSuccessful();

        // 提交空表单验证必填字段错误
        $form = $crawler->selectButton('Create')->form();

        try {
            // 直接提交空表单来测试验证
            $crawler = $client->submit($form);

            // 如果没有异常，检查响应状态码
            $this->assertResponseStatusCodeSame(422);
            $this->assertStringContainsString('should not be blank', $crawler->filter('.invalid-feedback')->text());
        } catch (\Exception $e) {
            // 验证异常是约束违规异常
            $this->assertStringContainsString('action_name', $e->getMessage());
        }
    }
}
