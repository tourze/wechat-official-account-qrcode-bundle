<?php

declare(strict_types=1);

namespace WechatOfficialAccountQrcodeBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use WechatOfficialAccountQrcodeBundle\Entity\QrcodeTicket;
use WechatOfficialAccountQrcodeBundle\Entity\ScanLog;

#[AdminCrud(routePath: '/wechat-official-account-qrcode/scan-log', routeName: 'wechat_official_account_qrcode_scan_log')]
final class ScanLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ScanLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('扫描记录')
            ->setEntityLabelInPlural('扫描记录')
            ->setSearchFields(['id', 'openId'])
            ->setDefaultSort(['id' => 'DESC'])
            ->setPaginatorPageSize(30)
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW, Action::EDIT)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield AssociationField::new('qrcode', '关联二维码')
            ->setRequired(true)
            ->setFormTypeOption('choice_label', function (QrcodeTicket $qrcodeTicket) {
                return sprintf('Ticket #%d (%s)', $qrcodeTicket->getId(), $qrcodeTicket->getActionName()?->getLabel() ?? '');
            })
            ->setHelp('扫描的二维码Ticket')
        ;

        yield TextField::new('openId', '扫描用户OpenID')
            ->setRequired(true)
            ->setHelp('扫描二维码的用户OpenID')
            ->setFormTypeOption('attr', ['maxlength' => 64])
        ;

        yield AssociationField::new('user', '关联用户')
            ->setHelp('如果有用户系统，这里显示关联的用户信息')
            ->hideOnIndex()
        ;

        yield DateTimeField::new('createTime', '扫描时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
            ->hideOnForm()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('qrcode', '关联二维码'))
            ->add(TextFilter::new('openId', '扫描用户OpenID'))
            ->add(EntityFilter::new('user', '关联用户'))
            ->add(DateTimeFilter::new('createTime', '扫描时间'))
        ;
    }
}
