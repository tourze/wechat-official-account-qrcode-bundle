<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeJump;

#[AdminCrud(routePath: '/wechat-official-account-qrcode/qrcode-jump', routeName: 'wechat_official_account_qrcode_qrcode_jump')]
final class QrcodeJumpCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QrcodeJump::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('二维码规则')
            ->setEntityLabelInPlural('二维码规则')
            ->setSearchFields(['id', 'prefix', 'appid', 'path'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield UrlField::new('prefix', '二维码URL前缀')
            ->setRequired(true)
            ->setHelp('服务号的带参二维码URL，必须是http://weixin.qq.com/q/开头的URL')
            ->setFormTypeOption('attr', ['placeholder' => 'http://weixin.qq.com/q/...', 'maxlength' => 200])
        ;

        yield TextField::new('appid', '小程序AppID')
            ->setRequired(true)
            ->setHelp('扫描二维码后要跳转的小程序的AppID')
            ->setFormTypeOption('attr', ['maxlength' => 64])
        ;

        yield TextField::new('path', '小程序页面路径')
            ->setRequired(true)
            ->setHelp('小程序功能页面路径')
            ->setFormTypeOption('attr', ['placeholder' => 'pages/index/index', 'maxlength' => 200])
        ;

        yield ChoiceField::new('edit', '编辑标志')
            ->setChoices([
                '新增二维码规则' => 0,
                '修改已有二维码规则' => 1,
            ])
            ->setRequired(true)
            ->setHelp('0表示新增二维码规则，1表示修改已有二维码规则')
        ;

        yield ChoiceField::new('state', '发布状态')
            ->setChoices([
                '未发布' => 0,
                '已发布' => 1,
            ])
            ->setRequired(true)
            ->setHelp('规则的发布状态')
            ->renderAsBadges([
                0 => 'warning',
                1 => 'success',
            ])
        ;

        yield DateTimeField::new('createTime', '创建时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
        ;

        yield DateTimeField::new('updateTime', '更新时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('prefix', '二维码URL前缀'))
            ->add(TextFilter::new('appid', '小程序AppID'))
            ->add(TextFilter::new('path', '小程序页面路径'))
            ->add(ChoiceFilter::new('edit', '编辑标志')->setChoices([
                '新增规则' => 0,
                '修改规则' => 1,
            ]))
            ->add(ChoiceFilter::new('state', '发布状态')->setChoices([
                '未发布' => 0,
                '已发布' => 1,
            ]))
            ->add(DateTimeFilter::new('createTime', '创建时间'))
            ->add(DateTimeFilter::new('updateTime', '更新时间'))
        ;
    }
}
